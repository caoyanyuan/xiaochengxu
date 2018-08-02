import {Config} from 'config.js'
class Base{
    constructor(){
        this.baseRequestUrl = Config.baseUrl;
    }

    request(params){
        var url = this.baseRequestUrl + params.url;
        var method = params.type ? params.type : 'GET';
        wx.request({
            url:url,
            type:method,
            data:params.data,
            header: {
                'content-type':'application/json',
                'token':wx.getStorageSync('token')
            },
            success:function (res) {
              params.sCallback && params.sCallback(res.data);
            },
            error:function (err) {
              
            }
        })
    }

    getDataSet(event,key){
      return event.currentTarget.dataset[key];
    }
}

export {Base}