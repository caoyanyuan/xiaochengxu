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
use app\api\validate\TokenGet;

class Token extends BaseModel
{
    public function getToken($code='')
    {
        (new TokenGet()) -> goCheck();
        $ut = new UserToken($code);
        $token = $ut ->get();
        return [
            'token'=>$token
        ];
    }
}