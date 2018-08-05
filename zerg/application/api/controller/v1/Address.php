<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20
 * Time: 17:17
 */

namespace app\api\controller\v1;

use app\api\model\User as UserModel;
use app\api\model\UserAddress;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\lib\exception\ParameterException;
use app\lib\exception\SuccessMessage;

use app\lib\exception\UserException;
use think\Controller;
use think\Exception;


class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only'=>'createOrUpdate']
    ];

    //{"mobile":"18070157098","province":"广东","city":"深圳","country":"某某地方","detail":"盐田吧","name":"嘿嘿"}
    public function createOrUpdate(){
        $validate = new AddressNew();
        $validate -> goCheck();

        //根据请求传过来的token值获取uid
        //根据uid获取用户，
        //获取用户从客户端提取过来的数据
        //根据用户地址信息是否存在，从而判断是更新地址还是新建地址
        $uid = TokenService::getCurrentUID();
        $user = UserModel::get($uid);
        if(!$user){
            throw new UserException([
                'msg' => '该token对应的用户id在数据库里找不到用户记录'
            ]);
        }
        $dataArrays = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if(!$userAddress){
            $user->address()->save($dataArrays);
        }else{
            $user->address->save($dataArrays);
        }
        throw new SuccessMessage();
    }

    public function getAddress()
    {
        $uid = TokenService::getCurrentUID();
        if(!$uid){
            throw new ParameterException([
                'msg' => '用户id不能为空'
            ]);
        }
        $address = UserAddress::where('user_id','=',$uid)->find();
        return $address;
    }
}