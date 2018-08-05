<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17
 * Time: 17:36
 */

namespace app\api\controller\v1;


use app\api\model\BaseModel;
use app\api\service\UserToken;
use app\api\service\Token as TokenService;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;

class Token extends BaseModel
{
    public function getToken($code='')
    {
        (new TokenGet()) -> goCheck();
        $ut = new UserToken($code);
        $token = $ut -> getKey();
        return [
            'token'=>$token
        ];
    }

    public function verify($token)
    {
        if(!$token){
            throw new ParameterException([
                'msg' => '检测的token不能为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            $valid=>$valid
        ];
    }
}