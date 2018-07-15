<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/15
 * Time: 0:01
 */

namespace app\api\model;


use think\Model;

class BannerItem extends BaseModel
{
    public function img(){
        return $this->belongsTo('Image','img_id','id');
    }
    public $hidden = ['delete_time','update_time'];

    public function getTypeAttr($value,$data){
        $finalUrl = $value;
        $finalUrl = config('setting.img_prefix').$value;
        return $finalUrl;
    }
}