import {Cart} from "../cart/cart-model.js"
import {Address} from "../../utils/address.js"
import {Order} from 'order-model.js'

var cart = new Cart();
var address = new Address();
var order = new Order();

Page({
  
  data: {
    totalPrice:0,             //下单的总价格
    id: null,                  //订单id
    orderStatus: 0           //订单来源，也对应订单状态 0：来自购物车（购物车未支付）， 1：来自订单（已生成订单未支付）
  },

  onLoad: function (options) {
    if(options.from == 'cart'){
      this.setData({
        totalPrice: options.totalPrice,
        productsArr: cart.getCartDataFromLocal(),
        orderStatus: 0
      })
    }else{

    }
    this.getAddress();
  },

  onShow: function(){
    if (this.data.id) {
      var that = this;
      //下单后，支付成功或者失败后，点左上角返回时能够更新订单状态 所以放在onshow中
      var id = this.data.id;
      order.getOrderInfoById(id, (data) => {
        var addressInfo = data.snap_address;
        addressInfo.totalDetail = address.setAddressInfo(addressInfo);
        that.setData({
          orderStatus: data.status,
          productsArr: data.snap_items,
          account: data.total_price,
          addressInfo: addressInfo,
          basicInfo: {
            orderTime: data.create_time,
            orderNo: data.order_no
          },
        });
      });
    }
  },

  //获取地址
  getAddress: function(){
    address.getAddress((res)=>{
      var addressInfo = {
        name: res.name,
        mobile: res.mobile,
        totalDetail: res.totalDetail
      };
      this.setData({
        addressInfo: addressInfo
      });
    })
  }, 

  pay: function(){
    if(!this.data.addressInfo){
      this.showTips('下单提示','请填写你的收货地址');
    }
    if(this.data.orderStatus == 0){
      this.firstPay();
    }else{
      this.oneMoresPay();
    }
  }, 

  //第一次支付
  firstPay: function(){
    let products = this.formatOrderData();
    //支付第一步：下单
    order.placeOrder(products, (res) => {
      
        if(res.pass){
          this.data.id = res.order_id;
          this._execPay();
        }else{
          this._orderFail(res);
        }
    })
  },

  //支付第二步：拉起微信支付
  _execPay: function(){
    var id = this.data.id; 
    /*真实支付*/
    order.execPay(id, (payStatus)=>{
      if (payStatus > 0){
          this.deleteFromCart();
          var flag = payStatus == 2;
          wx.navigateTo({
            url: '/pay-result/pay-result?id'+id+'&flag='+flag
          })
      }
    })
    // var payStatus = 1;
    // this.deleteFromCart();
    // var flag = payStatus == 2;
    // wx.navigateTo({
    //   url: '../pay-result/pay-result?id='+id+'&flag='+flag
    // })
  },

  //从购物车中删除已下单的产品
  deleteFromCart: function(){
    var idx = [] ,arr = this.data.productsArr;
    for(var id in arr){
      idx.push(id);
    }    
    cart.delete(idx);
  },

  //生成订单失败：返回客户错误信息
  _orderFail: function(res){
    var pArr = res.pStatusArray,
        nameArr = [],
        str = "";
    for(var i=0;i<pArr.length;i++){
      if (!pArr[i].hasStock){
        let name = pArr[i].name;
        nameArr.push(pArr[i].name);
      }
    }
    str = nameArr.join(',');
    if(str.length > 12){
      str = str.substring(0, 12);
      if (nameArr.length > 1) {
        str += '等';
      }
    }
    this.showTips('下单失败', str + '缺货');
  },

  //整理出下单参数
  formatOrderData: function(){
    let tempArr = [],
        proArr = this.data.productsArr;
    for (var i = 0; i < proArr.length;i++){
      tempArr.push({
        'product_id': proArr[i].id,
        'count': proArr[i].counts 
      })
    }
    return tempArr;
  },

  //新增或编辑地址
  editAddress: function() {
    var that = this;
    wx.chooseAddress({
      success: function(result){
        var addressInfo = {
          name: result.userName,
          mobile: result.telNumber,
          totalDetail: address.setAddressInfo(result)
        };
        that.setData({
          addressInfo: addressInfo
        });

        //保存地址
        address.saveAddress(result,(flag,res)=>{
          if (!flag){
            that.showTips('地址提交失败',res.msg);
          }
        });
      }
    })
  },  

  //显示弹出框
  showTips: function(title, content){
    wx.showModal({
      title: title,
      content: content,
      showCancel: false
    })
  }
  
})