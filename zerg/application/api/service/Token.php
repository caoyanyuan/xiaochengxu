<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 16:54
 */

namespace app\api\service;


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
}