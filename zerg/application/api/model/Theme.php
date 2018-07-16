<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/15
 * Time: 17:32
 */

namespace app\api\model;


class Theme extends  BaseModel
{
    public function topicImg() {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public function headImg() {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    /**
     * 关联product，多对多关系
     */
    public function products() {
        return $this->belongsToMany(
            'Product', 'theme_product', 'product_id', 'theme_id');
    }

    public static function getThemeWithProducts($id) {
        $products = self::with('topicImg,headImg,products')->select($id);
        return $products;
    }


}