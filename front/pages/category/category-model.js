import {Base} from '../../utils/base.js'

export class Category extends Base{
  constructor(){
    super();
  }

  getCategoryType(sCallback){
    var params =  {
      url:'/category/all',
      sCallback:(res) => {
        sCallback && sCallback(res);
      }
    }
    this.request(params);
  }

  getProductsByCategory(id,sCallback){
    var params = {
      url:'/product/by_category',
      data:{
        id:id
      },
      sCallback: function (res) {
        sCallback && sCallback(res);
      }
    }
    this.request(params);
  }
}