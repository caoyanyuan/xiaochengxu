<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20
 * Time: 17:18
 */

namespace app\api\validate;


class addressNew extends BaseValidate
{
    protected $rule = [
        'mobile' => 'require|isNotEmpty',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
        'name' => 'require|isNotEmpty',
    ];
}