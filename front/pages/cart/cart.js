import {Cart} from 'cart-model.js'

var cart = new Cart();

Page({
  data:{
    cartData:[],
    selectedTypeCounts:0,        //选中类目
    selectedCounts:0,           //选中产品总个数
    totalPrice:0                //总价格
  },
  onLoad: function() {
  
  },
  onShow: function(){
    var tempObj = cart.getCartTotalCounts(true);
    this.setData({
      cartData: cart.getCartDataFromLocal(),
      selectedCounts: tempObj.counts1,
      selectedTypeCounts: tempObj.counts2,
      totalPrice: this._cacuTotalPriceAndCount(cart.getCartDataFromLocal()).totalPrice
    })
  },

  //每次更改购物车数据
  _resertCartData: function(){
    var newData = this._cacuTotalPriceAndCount(this.data.cartData);
    this.setData({
      cartData: this.data.cartData,
      totalPrice: newData.totalPrice,
      selectedCounts: newData.selectedCounts,
      selectedTypeCounts: newData.selectedTypeCounts
    });
  },

  //计算总金额和选择的商品总数
  _cacuTotalPriceAndCount: function(cartData){
    var tPrice = 0,
        selectedCounts = 0,
        selectedTypeCounts = 0;
    let multiple = 100;
    for(var i=0;i<cartData.length;i++){
      if(cartData[i].selectStatus){
        tPrice += cartData[i].price * cartData[i].counts *multiple;
        selectedCounts += cartData[i].counts;
        selectedTypeCounts++;
      }
    }

    return {
      totalPrice: tPrice/multiple,
      selectedCounts: selectedCounts,
      selectedTypeCounts: selectedTypeCounts
    };
  },

  //单选复选
  toggleSelect: function(event){
    var id = cart.getDataSet(event, 'id'),
        status = cart.getDataSet(event, 'status'),
        index = this._getIndexById(id);
    this.data.cartData[index].selectStatus = !status;
    this._resertCartData();
  },

  //根据id拿到商品下标
  _getIndexById: function(id){
    var cartData = this.data.cartData;
    for (var i=0;i<cartData.length; i++){
      if (cartData[i].id == id){
        return i;
      }
    }
  },

  //全选
  toggleSelectAll: function(event){
    var status = cart.getDataSet(event, 'status');
    var cataData = this.data.cartData;
    for(var i=0;i<cataData.length;i++){
      cataData[i].selectStatus = !status;
    }
    this.data.cartData = cataData;
    this._resertCartData();
  },

  //加减商品
  changeCounts: function(event){
    var id = cart.getDataSet(event, 'id'),
        type = cart.getDataSet(event, 'type'),
        index = this._getIndexById(id),
        count = 1;
    if(type == 'cut'){
      count = -1;
    }
    this.data.cartData[index].counts+=count;
    this._resertCartData(this.data.cartData);
  },

  //删除商品
  delete: function(event){
    var id = cart.getDataSet(event, 'id'),
        type = cart.getDataSet(event, 'type'),
        index = this._getIndexById(id);
    this.data.cartData.splice(index,1);
    this._resertCartData();
  },

  //离开页面时候更新缓存，切面思想：找一个切入点处理类似的需求
  onHide: function(){
    cart.execSetStorageSync(this.data.cartData);
  },

  //跳转到商品详情页
  onProductsItemTap: function(event){
    var id = cart.getDataSet(event, 'id');
    wx.navigateTo({
      url:'/pages/product/product?id='+id
    })
  },

  //提交订单 带入总金额和from
  submitOrder: function(){
    wx.navigateTo({
      url: '/pages/order/order?totalPrice=' + this.data.totalPrice + "from=cart"
    })
  }

})


