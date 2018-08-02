// pages/product/product.js
import {Product} from 'product-model.js';
import {Cart} from '../cart/cart-model.js'
var product = new Product();
var cart = new Cart();

Page({

  data: {
    id: '',  //当前产品id
    countsArray: [1,2,3,4],   //选择数量下拉列表
    countSelected: 1,          //当前选择数量
    currentTabsIndex: 0,      //选项卡当前index
    productInfo: "",           //当前产品详情
    cartTotalCounts: 0
  },

 
  onLoad: function (options) {
    this.data.id = options.id;
    this._load();
  },

  _load: function (){
    product.getProduct(this.data.id,(res)=>{
      this.setData({
        'cartTotalCounts': cart.getCartTotalCounts().counts1,
        'product':res
      });
      this.data.productInfo = res;
    })
  },

  //数量选择
  bindPickerChange: function (event){
    var _index = event.detail.value;
    this.setData({
      countSelected: this.data.countsArray[_index]
    });
  },

  //tab切换
  onTabsItemTap: function(event){
    var index = product.getDataSet(event,'index');
    this.setData({
      currentTabsIndex: index
    })
  },

  onAddingToCartTap: function(events){
    if(this.data.isFly){
      return;
    }
    this._flyToCartEffect(events)
    this.addToCart();
  },

  addToCart: function(){
    var item = {},
        arr = ['id','name','price','main_img_url'];
    for(var key in this.data.productInfo){
      
      if (arr.indexOf(key)>-1){
        item[key] = this.data.productInfo[key];
      }
    }
    cart.add(item,this.data.countSelected);
  },

  _flyToCartEffect:function(events){
    //获得当前点击的位置，距离可视区域左上角
    var touches=events.touches[0];
    var diff={
          x:'25px',
          y:25-touches.clientY+'px'
        },
        style='display: block;-webkit-transform:translate('+diff.x+','+diff.y+') rotate(350deg) scale(0)';  //移动距离
    this.setData({
      isFly:true,
      translateStyle:style
    });
    setTimeout(()=>{
      this.setData({
        isFly:false,
        translateStyle:'-webkit-transform: none;',  //恢复到最初状态
        isShake:true,
      });
      setTimeout(()=>{
        var counts = this.data.cartTotalCounts + this.data.countSelected;
        this.setData({
          isShake:false,
          cartTotalCounts:counts
        });
      },200);
    },1000);
  },

  onCartTap:function(){
    wx.switchTab({
      url:'/pages/cart/cart'
    })
  }
})