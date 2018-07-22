<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 16:54
 */

namespace app\api\service;

use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;

class Token
{
    public static function generateToken(){
        //随机字符串
        $random = getRandChar(32);
        //时间戳
        $timestamp =  $_SERVER['REQUEST_TIME_FLOAT'];
        //salt 配置的字符串
        $salt = config('secure.token_salt');

        //md5加密
        return md5($random.$timestamp.$salt);
    }

    public static function getCurrentTokenVar($key){
        //约定：所有的用户令牌都要放在http的header的信息里面
        $token = Request::instance()->header('token');
        $values = Cache::get($token);
        dump($values);
        if(!$values){
            throw new TokenException();
        }else{
            //$values 文件系统缓存的话就是字符串形式。转成数组处理。但如果是redis缓存，则可能直接就是数组了。
            if(!is_array($values)){
                $values = json_decode($values, true);
            }
            if(array_key_exists($key,$values)){
                return $values[$key];
            }else{
                throw new Exception('尝试获取的token的变量key不存在');
            }
        }
    }

    public static function getCurrentUID()
    {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    public static function needPrimaryScope()
    {
        
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope >= ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException('用户未登录');
        }
    }

    public function needExclusiveScope()
    {
        //难点就是获取用户的scope：是否已经登录用户。
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope == ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException('用户未登录');
        }
    }

}