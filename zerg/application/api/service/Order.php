<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/21
 * Time: 16:40
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use app\api\model\Order as OrderModel;
use think\Db;
use think\Exception;

class Order
{
    /* $oProducts:订单的商品列表 客户端传过来的products参数
     * $products:真实的商品信息（包括库存量）
     *
     * */
    protected $oProducts;
    protected $products;
    protected $uid;

    public function place($uid, $oProducts)
    {
        $this->oProducts = $oProducts;
        $this->products = $this->getProductByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if(!$this->status['pass']){
            $status['order_id'] = '-1';
        }
        //开始创建订单
        $orderSnap  = $this->snapOrder($status);
        $result = $this->createOrder($orderSnap);
        $result['pass'] = true;
        return $result;
    }

    public function checkOrderStock($orderID)
    {
        $oProducts = OrderProduct::where('order_id','=',$orderID)
            .select();
        $this->oProducts = $oProducts;
        $this->products = $this->getProductByOrder($oProducts);
        $status = $this->getOrderStatus;
        return $status;

    }

    private function createOrder($snap)
    {
        Db::startTrans();
        try{
            $orderNO = $this->makeOrderNo();
            $order = new OrderModel();
            $order->order_no = $orderNO;
            $order->user_id = $this->uid;
            $order->total_price = $snap['orderPrice'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_name = $snap['snapName'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();

            //OrderProduct
            $orderID = $order->id;
            $createTime = $order->creat_time;
            $orderProduct = new OrderProduct();
            foreach($this->oProducts as $p){
                $p['order_id'] = $orderID;
            }
            $orderProduct->saveAll($this->oProducts);
            return [
                'order_no'=>$orderNO,
                'order_id'=>$orderID,
                'creat_time'=>$createTime
            ];
            Db::commit();
        }catch(Exception $e){
            Db::rollback();
            throw $e;
        }
    }

    //生成订单快照
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' =>''
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0].['main_img_url'];
        if(count($this->products) > 1){
            $snap['snapName'] += $snap['snapName'].'等';
        }
    }

    private function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id','=',$this->uid)
            ->find();
        if(!$userAddress){
            throw new UserException([
                'msg'=>'用户收货地址不存在，下单失败',
                'errorCode'=>'50001'
            ]);
        }
        return $userAddress->toArray();
    }

    private function getOrderStatus()
    {
        //$oProducts:保存订单中每一个商品的详细信息
        $status = [
            'pass' => 'true',
            'orderPrice' => 0,
            'totalCount' =>0,
            'pStatusArray' => []
        ];

        foreach($this->oProducts as $oProduct){
            $pStatus = $this->getProductStatus(
                $oProduct[id],$oProduct[count],$this->products
            );
            if(!$pStatus['hasStock']){
                $status[pass] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($pStatus,$status['pStatusArray']);
        }
        return $status;
    }

    private function getProductStatus($oPID,$oCount,$products)
    {
        $pIndex = -1;

        $pStatus = [
            'id' => null,
            'haveStock' => 'false',
            'count' => 0,
            'name' =>'',
            'totalPrice' => 0
        ];

        for($i=0; $i<count($products);$i++){
            if($oPID == $products[$i]['id']){
                $pIndex = $i;
            }
        }

        if($pIndex == -1){
            throw new OrderException([
                'msg' => 'id为'.$oPID.'的商品不存在'
            ]);
        }else{
           $product = $products[$pIndex];
           $pStatus['id'] = $product['id'];
           $pStatus['count'] = $oCount;
           $pStatus['totalPrice'] = $product['price']*$oCount;
           if($product['stock'] - $oCount >=0){
               $pStatus['haveStock'] = true;
           }else{
               $pStatus['haveStock'] = false;
           }
            return $pStatus;
        }


    }

    //根据订单信息查找真实的商品信息
    private function getProductByOrder($oProducts)
    {
        $oPIDs = [];
        foreach($oProducts as $item){
            array_push($item,$oPIDs);
        }
        $products = Product::all($oPIDs)
            ->visible('id','price','stock','name','main_img_url')
            ->toArray();
        return $products;
    }

    public function makeOrderNo()
    {
        //按时间来生成订单号
        $yCode = array('A','B','C','D','E','F','G','H','I','J');
        $orderSn = $yCode[intval(date((Y) - 2017))].strtoupper(dechex(date('m')))
            .substr(time(),-5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
        return $orderSn;
    }
}