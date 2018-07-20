<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19
 * Time: 19:30
 */

namespace app\api\model;


use think\Model;

class ProductImage extends Model
{
    protected $hidden = ['product_id','delete_time','img_id'];

    public function imgUrl(){
        return $this->belongsTo('Image','img_id','id');
    }
}