<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/15
 * Time: 16:10
 */

namespace app\api\model;


class Image extends BaseModel
{
    public $hidden = ['id','delete_time','update_time','from'];

    public function getUrlAttr($value,$data){
        return $this->prefixImgUrl($value, $data);
    }
}