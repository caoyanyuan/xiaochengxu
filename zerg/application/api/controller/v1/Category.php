<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16
 * Time: 19:06
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;

class Category
{
    public function getAllCategories(){
        $categories = CategoryModel::all([],'img');
        return $categories;
    }
}