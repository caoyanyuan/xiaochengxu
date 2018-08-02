import {Base} from '../../utils/base.js'

class Theme extends Base{
  constructor(){
    super();
  }
  getTheme(id,sCallback){
    var param = {
      url: '/theme/' + id,
      sCallback: function (res) {
        sCallback && sCallback(res);
      }
    }
    this.request(param);
  }
}

export {Theme}
