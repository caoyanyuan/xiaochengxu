<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/29
 * Time: 12:45
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\api\service\Order as OrderService;
use app\api\model\Order;
use think\Exception;
use think\Log;

class WxNotify extends \WxPayNotify
{
   public function NotifyProcess($data, $msg)
   {
       if($data['result_code'] == 'SUCCESS'){
           $orderNo = $data['out_trade_no'];
           Db::startTrans();
           try{
               $order = Order::where('order_no','=',$orderNo)->lock(true)
                   ->find();
               if($order->status = OrderStatusEnum::UNPAID){
                   $service = new OrderService();
                   $status = $service->checkOrderStock($order->id);
                   if($status['pass']){
                       $this->updateOrderStatus($order->id, true);
                       $this->reduceStock($status);
                   }else{
                       $this->updateOrderStatus($order->id,false);                   }
               }
               Db::commit();
               return true;
           } catch (Exception $e){
               Db::rollback();
               Log::error($e);
               return false;
           }
       }
       return true;
   }

   private function reduceStock($status)
   {
       foreach($status[pStatusArray] as $pStatus){
           Product::where('id','=',$pStatus['id'])
               ->setDec('stock',$pStatus['count']);
       }
   }

   private function updateOrderStatus($id,$pass)
   {
        $status = $pass ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        Order::where('order_id','=',$id)
       ->update(['status'=>$status]);
   }

}