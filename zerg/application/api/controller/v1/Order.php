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
use app\api\service\Order as OrderService;

class Order extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only'=>'createOrUpdate']
    ];

    public function placeOrder()
    {
        //库存量检测是否有货，并生成订单
        (new OrderPlace())->goCheck();
        $oProducts = input('post.products/a'); //'/a'是获取数组的标志
        $uid = TokenService::getCurrentUID();
        $order = new OrderService;
        $result = $order->place($uid,$oProducts);
        return $result;
    }
}