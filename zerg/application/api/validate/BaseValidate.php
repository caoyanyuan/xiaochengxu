<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 18:23
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        $request = Request::instance();
        $params = $request->param();

        $result = $this->batch()->check($params);
        if(!$result){
            $error =  new ParameterException([
                'msg' => $this->getError()
            ]);
            throw $error;
        }else{
            return true;
        }
    }

    protected function isPositiveIntegar($value, $rule="",$data="",$field="")
    {
        if(is_numeric($value) && is_int($value+0) && ($value+0)> 0){
            return true;
        }else{
            return false;
        }
    }
}