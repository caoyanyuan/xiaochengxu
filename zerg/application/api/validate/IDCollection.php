<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/15
 * Time: 19:02
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
       'ids' => 'require | checkIDs'
    ];

    protected function checkIds($value)
    {
        $values = explode(',',$value);
        if(empty($values)){
            return false;
        }
        foreach($values as $id){

        }
    }

}