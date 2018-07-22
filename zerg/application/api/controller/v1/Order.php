<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/21
 * Time: 15:52
 */

namespace app\api\controller\v1;


use app\api\validate\OrderPlace;
use app\lib\enum\ScopeEnum;
use think\Controller;
use app\api\service\token as TokenService;

class Order extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only'=>'createOrUpdate']
    ];

    public function placeOrder()
    {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a'); //'/a'是获取数组的标志
        $uid = TokenService::getCurrentUID();
    }
}