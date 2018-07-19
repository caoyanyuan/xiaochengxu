<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/17
 * Time: 17:02
 */

namespace app\extra;


return [
    'app_id' => 'wx5593c02279376ea9',

    'app_secret' => '1de720c01666026565bc6815519a3be6',

        // 微信使用code换取用户openid及session_key的url地址
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?" .
        "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
];