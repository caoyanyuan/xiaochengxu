<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17
 * Time: 17:52
 */

namespace app\api\service;


use app\api\model\User as UserModel;
use app\api\service\Token;

use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;
use app\lib\enum\ScopeEnum;


class UserToken extends Token
{
    protected $code;
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code){
        $this->code = $code;
        $this->wxAppId = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'),
            $this->wxAppId,$this->wxAppSecret,$this->code);

    }

    public function getKey(){
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if(empty($wxResult)){
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this -> processLoginError($wxResult);
            }else{
                return $this -> grantToken($wxResult);
            }
        }
    }



    private function grantToken($wxResult){
        //拿到openid
        //数据库里面查询这个openid是否已经存在,如果已经存在则拿到user的id。如果不存在即新建user也拿id
        //根据拿到的id生成令牌,并记入缓存
        //将令牌返回客户端
        //key:令牌 value：wxResult uid scope
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenid($openid);
        if($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }
        $cacheValue = $this->prepareCacheValue($wxResult,$uid);
        $cacheKey = $this->saveToCache($cacheValue);
        return $cacheKey;
    }

    private function saveToCache($cacheValue){
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        $expire_in = config('setting.token_expire_in');
        $result = cache($key,$value,$expire_in);
        if(!$result){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10001
            ]);
        }
        return $key;
    }

    private function newUser($openid){
        $newUser = UserModel::create([
            'openid' => $openid
        ]);
        return $newUser->id;
    }

    private function prepareCacheValue($wxResult,$uid){
        $cacheValue = $wxResult;
        $cacheValue['uid'] = $uid;
        //scope代表权限
        $cacheValue['scope'] = ScopeEnum::User;
        return $cacheValue;
    }


    private function processLoginError($wxResult){
        throw new WeChatException(
            [
                'msg' => $wxResult['errmsg'],
                'errorCode' => $wxResult['errcode']
            ]);
    }

}