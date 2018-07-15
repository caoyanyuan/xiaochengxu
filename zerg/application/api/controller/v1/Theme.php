<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/15
 * Time: 17:32
 */

namespace app\api\controller\v1;


use app\api\validate\IDCollection;

class Theme
{
    public function getSimpleList($ids = '')
    {
        (new IDCollection()) -> goCheck();
    }
}