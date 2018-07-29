//index.js
//获取应用实例
import { Index } from 'index-model.js'
var index = new Index();

Page({
  data: {

  },
  onLoad: function () {
    this._loadData();
  },
  _loadData:function(){
    var id = 1;
    var data = index.getBanner(id,(res)=>{
        this.setData({
            'bannerArr':res
        });
    })
    index.getTheme((res)=>{
        this.setData({
            'themeArr':res
        });

    })
  }
})
