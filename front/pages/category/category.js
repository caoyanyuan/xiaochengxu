import { Category } from 'category-model.js';
var category = new Category();  //实例化 home 的推荐页面
Page({
  data: {
    transClassArr: ['tanslate0', 'tanslate1', 'tanslate2', 'tanslate3', 'tanslate4', 'tanslate5'],
    currentMenuIndex : 0,
  },
  onLoad: function () {
    this._load();
  },

  _load: function() {
    category.getCategoryType((cateData)=>{
      this.setData({
        categoryTypeArr: cateData
      })

      //首次加载第一个类目下的商品
      category.getProductsByCategory(cateData[0].id, (data)=>{
        this.setData(this.getObjForBind(0, data));
      })
    })
  },

  changeCategory: function(event) {
    var id = category.getDataSet(event,'id');
    var index = category.getDataSet(event, 'index');
    this.setData({
      currentMenuIndex:index
    });
    
    //判断是否已经加载过该类目商品
    if(!this.isLoadThatData(index)){
      category.getProductsByCategory(this.data.categoryTypeArr[index].id, (data) => {
        this.setData(this.getObjForBind(index, data));
      })
    }
  },

  isLoadThatData: function(index){
    if (this.data['categoryInfo'+index]){
      return true;
    }else{
      return false;
    }
  },

  //生成绑定数据
  getObjForBind:function(index, data) {
    var obj = {},
        arr = [0,1,2,3,4,5],
        cateData = this.data.categoryTypeArr;
        
    for(var item in arr){
      if(item == index){
        obj['categoryInfo' + index] = {
          topImgUrl: cateData[index].img.url,
          title: cateData[index].name,
          products: data
        }
        return obj;
      }
      
    }
  },
 
  /*跳转到商品详情*/
  onProductsItemTap: function (event) {
    var id = category.getDataSet(event, 'id');
    wx.navigateTo({
      url: '../product/product?id=' + id
    })
  },

})