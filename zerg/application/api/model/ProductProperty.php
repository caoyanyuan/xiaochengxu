<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/19
 * Time: 19:30
 */

namespace app\api\model;


use think\Model;

class ProductProperty extends Model
{
    protected $hidden=['delete_time','product_id','update_time'];
}