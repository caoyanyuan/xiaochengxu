<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/24
 * Time: 16:21
 */

namespace app\api\controller\v1;


use app\api\service\WxNotify;
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

    public function receiveNotify()
    {
        $notify = new WxNotify();
        $notify->Handle();
    }

    //转发微信接口进行debug调试
    public function redirectNotify()
    {
        $xmlData = file_get_contents('php://input');
        $result = curl_post_raw('http:/zerg.cn/api/v1/pay/re_notify?XDEBUG_SESSION_START=13133',
            $xmlData);
        return $result;
    }

}