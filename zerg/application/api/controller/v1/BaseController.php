<?php
/**
 * Created by PhpStorm.
 * User: zhongrc
 * Date: 2018/7/21
 * Time: 16:15
 */

namespace app\api\controller\v1;


use think\Controller;
use app\api\service\token as TokenService;

class BaseController extends Controller
{
    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }

}