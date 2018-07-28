<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/24
 * Time: 16:21
 */

namespace app\api\controller\v1;


use app\api\validate\IdPositiveInt;
use app\api\service\Pay as PayModel;


class Pay
{
    public function getPreOrder($id='')
    {
        (new IdPositiveInt())->goCheck();
        $result = (new PayModel($id))->pay();
        return $result;
    }
}