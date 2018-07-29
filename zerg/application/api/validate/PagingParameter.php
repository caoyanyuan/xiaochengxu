<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/29
 * Time: 14:14
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];

    protected $message = [
        'page' => '分页参数必须为正整数',
        'size' => '分页参数必须为正整数'
    ];
}