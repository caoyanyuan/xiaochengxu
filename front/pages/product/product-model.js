import {Base} from '../../utils/base.js'

export class Product extends Base{
  constructor(){
    super();
  }
  getProduct(id,sCallback){
    var param = {
      url:'/product/'+id,
      sCallback:(res)=>{
        sCallback && sCallback(res);
      }
    };
    this.request(param);
  }
}

