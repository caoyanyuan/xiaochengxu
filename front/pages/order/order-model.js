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
    callback中 0：库存等原因导致订单不能支付（第三次库存量检测）
    1：支付失败获取取消  2：支付成功
   */
  execPay(orderID, callback){
    var params = {
      url: '/pay/pre_order',
      type: 'POST',
      data: {id: orderID},
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
    this.request(params);
  }

  /*获取订单快照*/
  getOrderInfoById(orderID, callback){
    var params = {
      url:'/order/'+orderID,
      sCallback: function(res){
        callback && callback(res);
      }
    }; 
    this.request(params);   
  }

  //获取用户订单 分页：pageIndex
  getOrdersByUser(pageIndex,size,callback){
    var params = {
      url: '/order/by_user',
      data: {
        page: pageIndex,
        size: size
      },
      sCallback: function(res){
        callback && callback(res);
      }
    }
    this.request(params);
  }
}

