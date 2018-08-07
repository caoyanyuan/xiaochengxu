import { My } from 'my-model.js'
import { Address } from '../../utils/address.js'
import { Order } from '../order/order-model.js'

var my = new My();
var address = new Address();
var order = new Order();

Page({

  data: {
    isHaveAuth: false,
    pageIndex: 1,
    size: 10,
    isLoadedAll: false,
    orderArr: []
  },

  onLoad: function (options) {
    this.load();
  },

  onShow: function(){
    if (wx.getStorageSync(order._storageKey)){
      this.refresh();
    }
  },

  refresh: function(){
    this._getOrdersByUser();
    this.data.pageIndex = 1;
    this.data.isLoadedAll = false;
    this.setData({
      orderArr: []
    })
  },

  load: function(){
    this.getUserInfo();
    address.getAddress((data) => {
      var addressInfo = {
        name: data.name,
        mobile: data.mobile,
        totalDetail: data.totalDetail
      };
      this.setData({
        addressInfo: addressInfo
      })
    })
    this._getOrdersByUser();
  },

  //获取用户信息
  getUserInfo: function(){
    var that = this;
    my.getUserInfo((flag, data) => {
      if (flag) {
        this.setData({
          isHaveAuth: true
        });
      }
      this.setData({
        userInfo: data
      })
    });
  },

  //新增编辑地址
  editAddress: function(){
    var that = this;
    wx.chooseAddress({
      success: function (data) {
        let addressInfo = {
          name: data.userName,
          mobile: data.telNumber,
          totalDetail: address.setAddressInfo(data)
        }
        that.setData({
          addressInfo: addressInfo
        })
        address.saveAddress(data, (flag, res) => {
          console.log(res);
        })
      },
    })
    
  },

  //获取订单
  _getOrdersByUser: function(){
    var that = this;
    order.getOrdersByUser(this.data.pageIndex,this.data.size,(res)=>{
      if (res.data.length == that.data.size){
        this.data.orderArr.push.apply(this.data.orderArr, res.data);
        this.setData({
          orderArr: this.data.orderArr
        })
      }else{
        this.isLoadedAll = true;
      }
    })
  },

  //付款
  rePay: function(event){
    var id = my.getDataSet(event, 'id'),
        index = my.getDataSet(event, 'index');
    this._execPay(id, index)
  },
  
  _execPay: function (id, index) {
    order.execPay(id, (payStatus) => {
      if (payStatus > 0) {
        var flag = payStatus == 2;
        if(flag){
          this.orderArr.splice(index, 1);
        }
        wx.navigateTo({
          url: '/pay-result/pay-result?id' + id + '&flag=' + flag
        })
      }
    })
  },

  //下拉加载产品数据
  onReachBottom: function(){
    if(!this.data.isLoadedAll){
      this.data.pageIndex += 1;
      this._getOrdersByUser()
    }
  },

  //跳转到order详情
  showOrderDetailInfo: function(event){
    var id = my.getDataSet(event,'id');
    wx.navigateTo({
      url: '../order/order?from=order&id='+id
    })
  }

})