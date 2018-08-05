import {Base} from '../../utils/base.js'

export class Order extends Base{
  constructor(){
    super();
    this._storageKey = 'newOrder';
  }

  //下单
  placeOrder(products, callback){
    var that = this;
    var params = {
      url: '/order',
      type: 'POST',
      data: {
        products: products
      },
      sCallback: function(data){
        wx.setStorageSync(that._storageKey, 'true');
        callback && callback(data);
      }
    }
    this.request(params);
  }

  /*
  * 生成预订单拿到签名并拉起微信支付
    callback中 0：库存等原因导致订单不能支付（第三个库存量检测）
    1：支付失败获取取消  2：支付成功
   */
  execPay(orderNO, callback){
    var params = {
      url: '/order/pre_order',
      method: 'POST',
      data: {id: orderNO},
      sCallback: function(data){
        var timestamp = data.timestamp;
        if(timestamp){
          wx.requestPayment({
            timeStamp: timestamp,
            nonceStr: data.nonceStr,
            package: data.package,
            signType: data.signType,
            paySign: data.paySign,
            success: function() {
              callback && callback(2);
            },error: function(){
              callback && callback(1);
            }
          })
        }else{
          callback && callback(0);
        }
      }
    }

  }
}

