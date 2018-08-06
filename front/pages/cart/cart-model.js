import {Base} from '../../utils/base.js'

export class Cart extends Base{
  constructor(){
    super();
    this._storageKey = 'cart';
  }

  /**
   * 获取所有产品以及选中的产品
   */
  getCartDataFromLocal(flag){
    var res = wx.getStorageSync(this._storageKey);
    if(!res){
      res = [];
    }
    if(flag){
        let newRes = [];
        for(let i=0;i++;i<res.length){
          if(res[i].selectStatus){
            newRes.push(res[i]);
          }
        }
    }
    return res;
  }

  /**
   * 计算购物车的产品总个数（counts1）和产品总类目（counts2）
   * flag: 是否是拿选中时候的
   */
  getCartTotalCounts(flag){
    var cartData = this.getCartDataFromLocal();
    var counts1 = 0,
        counts2 = 0;
    if(flag){
      for(var i=0; i<cartData.length; i++){
        if(cartData[i].selectStatus){
          counts1 += cartData[i].counts;
          counts2 ++;
        }
      }
    }else{
      for(var i=0; i<cartData.length; i++){
          counts1 += cartData[i].counts;
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
    var cartData = this.getCartDataFromLocal();
    var isHadInfo = this._isHasThat(item.id, cartData);
   
    //已有商品
    if(isHadInfo.index == -1){
      item.counts = counts;
      item.selectStatus = true;
      cartData.push(item);
    }else{
      cartData[isHadInfo['index']].counts += counts;
    }

    wx.setStorageSync(this._storageKey, cartData);
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

  //删除某个或某些商品
  delete(ids){
    if(!(ids instanceof Array)){
      ids = [ids];
    }
    var cartData = this.getCartDataFromLocal();
    for(var id in ids){
      let index = this._isHasThat(id, cartData);
      cartData.splice(index, 1);
    }
    this.execSetStorageSync(cartData);
  }

  execSetStorageSync(cartData){
    wx.setStorageSync(this._storageKey, cartData);
  }

}