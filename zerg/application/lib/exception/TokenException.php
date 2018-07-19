<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19
 * Time: 16:34
 */

namespace app\lib\exception;


use think\Exception;

class TokenException extends Exception
{
    public $code = 401;
    public $msg = "Token已过期或无效Token";
    public $errorCode = 10001;

}