<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 14:07
 */

namespace app\lib\exception;


use think\Exception;

class WeChatException extends BaseException
{
    public $code = 404;
    public $msg = '获取openid失败，请检查code是否合法';
    public $errorCode = 30000;
}