//index.js
//获取应用实例
import { Index } from 'index-model.js'
var index = new Index();

Page({
  data: {

  },
  onLoad: function () {
    this._loadData();
    wx.setNavigationBarTitle({
      title: '零食商贩',
    })
  },
  _loadData:function(){
    index.getBanner(1,(res)=>{
        this.setData({
            'bannerArr':res
        });
    })

    index.getTheme((res)=>{
        this.setData({
            'themeArr':res
        });
    })

    index.getProduct((res)=>{
      this.setData({
        'productsArr':res
      });
    })

  
  },
  onProductItemTap:function(event){
    var id = index.getDataSet(event, 'id');
    wx.navigateTo({
      url:'../product/product?id='+id
    });
  },
  onThemeItemTap:function(event){
    var id = index.getDataSet(event, 'id');
    var name = index.getDataSet(event, 'name');
    wx.navigateTo({
      url: '../theme/theme?id=' + id + '&name=' + name
    });
  }

})
