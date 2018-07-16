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
    public $hidden = ['delete_time', 'main_img_id', 'pivot', 'from', 'category_id',
        'create_time', 'update_time'];

    protected function getMainImgUrlAttr($value,$data){
        return $this->prefixImgUrl($value, $data);
    }
}