import {Base} from '../../utils/base.js'

class Index extends Base{
    constructor(){
        super();
    }
    getBanner(id, callback){
        var params = {
            url:'/banner/'+ id,
            sCallBack: (res)=>{
                callback && callback(res.items);
            }
        }
        this.request(params);
    }
    getTheme(callback){
        var params = {
            url:'/themes/?ids=1,2,3',
            sCallBack: (res)=>{
                callback && callback(res);
            }
        }
        this.request(params);
    }
}

export {Index}