<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/24
 * Time: 16:23
 */

namespace app\api\service;
use app\lib\enum\orderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\service\Order;
use app\api\model\Order as OrderModel;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderID;

    function __construct($orderID)
    {
        if (!$orderID)
        {
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
    }

   public function pay()
    {
        //对订单号进行检测
        $this->checkOrderIdValid();
        //再次检测库存量
        $order = new Order();
        $status = $order->checkOrderStock($this->orderID);
        return $this->makeWxPreOrder($status['orderPrice']);
    }

    private function makeWxPreOrder($totalPrice)
    {
        $openid = Token::getCurrentTokenVar('openid');
        if(!$openid){
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderID);
        $wxOrderData->SetTrade_type('JSAPT');
        $wxOrderData->SetTotal_fee($totalPrice*100);
        $wxOrderData->SetBody('零食商城');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url('');
        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if($wxOrder['return_code']!='SUCCESS' || $wxOrder['result_code']!= 'SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
        }
        $this->recordOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }

    private function recordOrder($wxOrder)
    {
        OrderModel::where('id','=',$this->orderID)
            ->update(['prepay'=>$wxOrder['prepay_id']]);
    }

    private function checkOrderIdValid()
    {
        $order = OrderModel::where('id','=',$this->orderID)->find();
        if(!$order){
            throw new OrderException([
                'msg'=>'订单找不到,请检查订单id',
                'errorCode'=>50002
            ]);
        }
        if(!Token::isValidOperator($order->user_id)){
            throw new TokenException([
                'msg'=>'订单所属用户与当前token用户不匹配',
                'errorCode'=>50003
            ]);
        }
        if($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg'=>'订单已经支付过了',
                'errorCode'=>50004,
                'code'=>400
            ]);
        }
    }

}