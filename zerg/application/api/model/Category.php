<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16
 * Time: 19:04
 */

namespace app\api\model;


class Category extends BaseModel
{
    public function img(){
        return $this->belongsTo('Image','topic_img_id','id');
    }
}