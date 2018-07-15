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
    public function topicImg()
    {
        return $this->belongsTo('Imgae','top_img_id','id');
    }

    public function headImg()
    {
        return $this->belongsTo('Imgae','head_img_id','id');
    }
}