import {Base} from '../../utils/base.js'

export class Cart extends Base{
  constructor(){
    super();
    this._storageKey = 'cart';
  }

  getCartDataFromLocal(){
    var res = wx.getStorageSync(this._storageKey);
    if(!res){
      res = [];
    }
    return res;
  }

  /**
   * 计算购物车的产品总个数（counts1）和产品总类目（counts2）
   * flag: 是否是拿选中时候的
   */
  getCartTotalCounts(flag){
    var cartArr = this.getCartDataFromLocal();
    var counts1 = 0,
        counts2 = 0;
    if(flag){
      for(var i=0; i<cartArr.length; i++){
        if(cartArr[i].selectStatus){
          counts1 += cartArr[i].counts;
          counts2 ++;
        }
      }
    }else{
      for(var i=0; i<cartArr.length; i++){
          counts1 += cartArr[i].counts;
          counts2 ++;
      }
    }

    return {
      counts1: counts1,
      counts2: counts2
    };
  }

  //加入购物车，
  add(item, counts){
    var cartArr = this.getCartDataFromLocal();
    var isHadInfo = this._isHasThat(item.id, cartArr);
   
    //已有商品
    if(isHadInfo.index == -1){
      item.counts = counts;
      item.selectStatus = true;
      cartArr.push(item);
    }else{
      cartArr[isHadInfo['index']].counts += counts;
    }

    wx.setStorageSync(this._storageKey, cartArr);
  }

  //查询购物车中是否有这个产品
  _isHasThat(id, arr){
    var result = {
      index: -1
    };
    for(var i=0; i<arr.length; i++){
      if(arr[i].id == id){
          result = {
            index: i,
            data: arr[i]
          }
      }
    }
    return result;
  }

  execSetStorageSync(cartArr){
    wx.setStorageSync(this._storageKey, cartArr);
  }

}