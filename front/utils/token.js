import {Config} from "config.js" 

export class Token {

  constructor(){
    this.getUrl = Config.baseUrl + '/token/user';
    this.verifyUrl = Config.baseUrl + '/token/verify';
    
  }
  //对token进行初始化验证：有则验证，无则获取
  verify(){
    let token = wx.getStorageSync('token');
    if(!token){
      this.getTokenFromServer();
    }else{
      this.verifyFromServer(token);
    }
  }

  //获取token
  getTokenFromServer(callback) {
    let that = this;
    wx.login({
      success: function(res){
        wx.request({
          url: that.getUrl,
          method: 'POST',
          data: {
            code: res.code
          },
          success: function (res) {
            wx.setStorageSync('token', res.data.token);
            callback && callback(res);
          }
        })
      }
    })
  }

  //携带token去服务器校验token
  verifyFromServer(token) {
    var that = this;
    wx.request({
      url: this.verifyUrl,
      method: 'POST',
      data: {
        token: token
      },
      success: function(res){
        if(!res){
          that.getTokenFromServer();
        }
      }
    })
  }


}