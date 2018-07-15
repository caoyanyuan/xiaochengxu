<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/12
 * Time: 18:57
 */

namespace app\api\model;


use think\Db;
use think\Exception;
use think\Model;

class Banner extends BaseModel
{
    protected  $hidden = ['delete_time','update_time'];
    public function items(){
        return $this->hasMany('BannerItem','banner_id','id');
    }
    public static function getBannerById($id){

        $result = self::with(['items','items.img'])->find($id);
        return $result;
    }
}