<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17
 * Time: 17:52
 */

namespace app\api\model;


class User extends BaseModel
{
    public static function getByOpenid($openid)
    {
        $user = User::where('openid','=',$openid)
            ->find();
        return $user;
    }
}