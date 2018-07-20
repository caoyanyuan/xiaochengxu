<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16
 * Time: 18:08
 */

namespace app\api\controller\v1;

use app\api\validate\Count;
use app\api\Model\Product as ProductModel;
use app\api\validate\IdPositiveInt;
use app\lib\exception\ProductException;


class Product
{
    public function getRecent($count=15)
    {
        (new Count()) -> goCheck();
        $products = ProductModel::getRecent($count);
        if($products ->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    public function getAllInCategory($id){
        (new IdPositiveInt()) -> goCheck();
        $products = ProductModel::getProductByCategoryId($id);
        if($products ->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return $products;
    }

    public function getOne($id){
        $product = ProductModel::getDetail($id);
        return $product;
    }
}