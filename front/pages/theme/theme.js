import {Theme} from "theme-model.js"

var theme = new Theme();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    name:'',
    id:""
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.data.name = options.name;
    this.data.id = options.id
    wx.setNavigationBarTitle({
      title: this.data.name,
    })
    this._load();
  },

  _load:function(){
    theme.getTheme(this.data.id,(res)=>{
      this.setData({
        themeInfo:res[0]
      });
    })
  },

  onProductItemTap: function (event) {
    var id = theme.getDataSet(event, 'id');
    wx.navigateTo({
      url: '../product/product?id=' + id
    });
  },
})