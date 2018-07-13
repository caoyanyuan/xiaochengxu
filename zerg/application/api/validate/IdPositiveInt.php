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

    protected function isPositiveIntegar($value, $rule="",$data="",$field="")
    {
        if(is_numeric($value) && is_int($value+0) && ($value+0)> 0){
            return true;
        }else{
            return $field.'必须为正整数';
        }
    }
}