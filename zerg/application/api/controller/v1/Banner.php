<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/10
 * Time: 18:51
 */

namespace app\api\controller\v1;


use app\api\model\Banner as BannerModel;
use app\api\validate\IdPositiveInt;
use app\api\validate\TestVali;
use app\lib\exception\BannerMissException;
use think\Exception;


class Banner
{
    //$id banner的id 获取指定id的banner信息
    public function getBanner($id)
    {
        (new IdPositiveInt() ) -> goCheck();
        $banner = BannerModel::getBannerById($id);
        return $banner;

    }
}