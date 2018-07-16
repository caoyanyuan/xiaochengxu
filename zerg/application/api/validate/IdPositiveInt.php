<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 11:15
 */

namespace app\api\validate;


use think\Validate;

class IdPositiveInt extends BaseValidate
{
    protected  $rule = [
        'id' => 'require|isPositiveIntegar'
    ];

    protected $message = [
        'id' => 'id必须为正整数'
    ];

}