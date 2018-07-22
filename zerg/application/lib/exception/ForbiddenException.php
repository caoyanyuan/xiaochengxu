<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/21
 * Time: 15:20
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
   public $code = '401';
   public $msg = '未授权或者权限不够';
   public $errorCode = 10001;
}