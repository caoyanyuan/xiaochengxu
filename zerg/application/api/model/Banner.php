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

class Banner extends Model
{
    public static function getBannerById($id){

       /* $result = Db::table('banner_item')->where('banner_id','=',$id)
            ->find();*/
        $result = Db::table('banner_item')
            ->where(function($query) use ($id){
                $query->where('banner_id','=',$id);
            })
            ->select();
        return json($result);
    }
}