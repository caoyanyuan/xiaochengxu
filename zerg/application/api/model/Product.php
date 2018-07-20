<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/15
 * Time: 17:32
 */

namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = ['delete_time', 'main_img_id', 'pivot', 'from', 'category_id',
        'create_time', 'update_time'];

    public function imgs(){
        return $this->hasMany('ProductImage','product_id','id');
    }

    //注意这里一定要return，不然报错难找
    public function properties(){
        return $this->hasMany('ProductProperty','product_id','id');
    }

    protected function getMainImgUrlAttr($value,$data){
        return $this->prefixImgUrl($value, $data);
    }

    //create_time:排序字段，desc：降序
    public static function getRecent($count){
        $products = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $products;
    }

    public static function getProductByCategoryId($CategoryId){
        $products = self::where('category_id','=',$CategoryId)
            ->select();
        return $products;
    }

    //商品詳情
    public static function getDetail($id){
        //返回的是query
        $product = self::with([
            'imgs'=>function($query){
                $query->with(['imgUrl'])
                ->order('order','asc');
            }
        ])
            ->with('properties')
            ->find($id);
        return $product;
    }
}











