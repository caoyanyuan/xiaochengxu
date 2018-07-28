<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//banner
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner'); //三段式 模块 / 控制器 / 方法名

//theme
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');

/*Route::get('api/:version/product/recent','api/:version.Product/getRecent');
Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
Route::get('api/:version/product/:id','api/:version.Product/getOne');*/

//product
Route::group('api/:version/product',function(){
    Route::get('/recent','api/:version.Product/getRecent');
    Route::get('/by_category','api/:version.Product/getAllInCategory');
    Route::get('/:id','api/:verison.Product/getOne',[],['id'=>'/d+']);
});

//category
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

//login
Route::post('api/:version/token/user','api/:version.Token/getToken');

//Address
Route::post('api/:verison/address','api/:verison.Address/createOrUpdate');


//Order
Route::post('api/:verison/order','api/:verison.Order/placeOrder');
Route::post('api/:verison/pay/pre_order','api/:verison.Pay/getPreOrder');