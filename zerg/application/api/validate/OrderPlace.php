<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/21
 * Time: 16:24
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
    //products数据结构
    protected $products = [
        [
            'product_id' => 1,
            'count' => 3
        ],
        [
            'product_id' => 2,
            'count' => 3
        ]
    ];

    protected $rule = [
        'products' => 'checkProducts'
    ];
    protected $singRule = [
        'product_id' => 'isPositiveInt'
    ];

    //对数组类型进行校验，
    //验证是不是数组，验证数组每一个子元素。子数组每一个选项都是正整数
    protected function checkProducts($values)
    {
        if(!is_array($values)){
            throw new ParameterException([
                'msg'=>'商品参数必须是数组'
            ]);
        }
        if(empty($values)){
            throw new ParameterException([
                'msg'=>'商品列表不能为空'
            ]);
        }

        foreach($values as $value){
            $this->checkSingleProduct($value);
        }
        return true;
    }

    protected function checkSingleProduct($value)
    {
        //巧妙调用独立验证的方式
        $validate = new BaseValidate($this->singRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg'=>'商品列表参数错误'
            ]);
        }
    }
}