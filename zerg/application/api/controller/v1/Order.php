<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/21
 * Time: 15:52
 */

namespace app\api\controller\v1;


use app\api\validate\IdPositiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\lib\enum\ScopeEnum;
use app\lib\exception\OrderException;
use think\Controller;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;

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

    public function getSummaryByUser($page=1,$size=15)
    {
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUID();
        $order = OrderModel::getSummaryByUser($uid,$page,$size);
        return $order;
    }

    public function getDetail($id)
    {
        (new IdPositiveInt())->goCheck();
        $order = OrderModel::get($id);
        if(!$order){
            throw new OrderException();
        }
        return $order;
    }
}