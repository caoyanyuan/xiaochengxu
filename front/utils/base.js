import {Config} from 'config.js'
import {Token} from 'token.js'

class Base{
    constructor(){
        this.baseRequestUrl = Config.baseUrl;
    }

    /*
      noRefetch: 授权失败的401只重试一次拿token，加入标志位以防止无数次调用request函数
    */
    request(params, noRefetch){
        var that = this;
        var url = this.baseRequestUrl + params.url;
        var method = params.type ? params.type : 'GET';
        wx.request({
            url: url,
            method: method,
            data: params.data,
            header: {
                'content-type':'application/json',
                'token':wx.getStorageSync('token')
            },
            success: function (res) {
              var code = res.statusCode.toString(),
                  startChar = code.charAt(0);
              if(startChar == 2){
                params.sCallback && params.sCallback(res.data);
              }else{
                if (code == 401 && !noRefetch){
                  that._reFetch(params);
                }
                if(noRefetch){ //重试之后依然报错，则传出报错函数
                  params.eCallback && params.eCallback(res.data);
                }
              }
            },
            error: function (err) {
              
            }
        })
    }

    // 当“接口”出现401错误时候。再去拿一次token。然后再调用一次“接口”
    _reFetch(params){
      var token = new Token();
      token.getTokenFromServer((res) => {
        this.request(params, true);
      });
      
    }

    getDataSet(event,key){
      return event.currentTarget.dataset[key];
    }
}

export {Base}