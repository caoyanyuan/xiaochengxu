import {Base} from '../../utils/base.js'

export class My extends Base{
  constructor(){
    super();
  }

  getUserInfo(callback){
    var that = this;
    wx.getUserInfo({
      withCredentials: true,
      lang: '',
      success: function (res) { 
        typeof (callback) == 'function' && callback(true, res.userInfo);
        that.updateUserInfo(res.nickName);
      },
      fail: function (res) {
        typeof (callback) == "function" && callback(false, {
            avatarUrl: '../../imgs/icon/user@default.png',
            nickName: 'username'
        });
       },
      complete: function (res) { },
    })
  }
  
  updateUserInfo(nickName){

  }
}