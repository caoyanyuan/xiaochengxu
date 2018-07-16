<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/15
 * Time: 17:32
 */

namespace app\api\controller\v1;


use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IdPositiveInt;

class Theme
{
    public function getSimpleList($ids = '')
    {
        (new IDCollection()) -> goCheck();
        $ids = explode(',', $ids);
        $result = ThemeModel::with('topicImg,headImg')->select($ids);

        if (count($result) < 1) {
            throw new ThemeException();
        }

        return $result;
    }

    public function getComplexOne($id)
    {
        (new IdPositiveInt()) -> goCheck();
        $result = ThemeModel::getThemeWithProducts($id);
        return $result;
    }
}