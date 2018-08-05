import {Base} from 'base.js'
import {Config} from 'config.js'

export class Address extends Base{
  constructor(){
    super()
  }

  //获取地址
  getAddress(callback){
    var that = this;
    let params = {
      url: '/address',
      sCallback: function(res) {
        if(res){
          res.totalDetail = that.setAddressInfo(res);
          callback && callback(res);
        }
      }
    }
    this.request(params);
  }

  //保存地址
  saveAddress(result, callback){
    result = this._formatAddress(result);
    let params = {
      url:'/address',
      type:'POST',
      data: result,
      sCallback: function(res){
        callback && callback(true, res);
      },eCallback: function(res){
        callback && callback(false, res);
      }
    };
    this.request(params);
  }

  //根据微信地址返回数据库格式地址
  _formatAddress(result){
    var data = {
      name: result.userName,
      province: result.provinceName,
      city: result.cityName,
      country: result.countyName,
      mobile: result.telNumber,
      detail: result.detailInfo
    };
    return data;
  }

  //根据省市区组装地址数据
  setAddressInfo(res){
    let province = res.provinceName || res.province,
        city = res.cityName || res.city,
        country = res.countyName || res.country,
        detail = res.detailInfo || res.detail,
        totalDetail = city + country + detail;
    if(!this._isDirectCity(province)){
      totalDetail = province + totalDetail;
    }
    return totalDetail;
  }

  //是否为直辖市
  _isDirectCity(province){
    let arr = ['北京市','上海市','天津市','重庆市'];
    if (arr.indexOf(province)>-1){
      return true;
    }
    return false;
  }
}