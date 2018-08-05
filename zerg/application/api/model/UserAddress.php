<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20
 * Time: 17:23
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    protected $hidden = ['id','user_id','create_time','delete_time'];
}