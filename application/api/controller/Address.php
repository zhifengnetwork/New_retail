<?php
/**
 * Created by PhpStorm.
 * User: zgp
 * Date: 2019/7/2 0002
 * Time: 17:51
 */

namespace app\api\controller;

use app\common\controller\ApiAbstract;
use app\common\model\Users;
use app\common\logic\UsersLogic;
use app\common\util\jwt\JWT;
use think\Config;
use think\Db;
use think\Exception;
use think\Request;
use app\api\Model\UserAddr;

class Address extends ApiAbstract
{

    
      /**
     * @api {POST} /address/addressList 地址列表
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    token              token*（必填）
     * @apiParamExample {json} 请求数据:
     * {
     *      "token":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {"status":200,
     * "msg":"success",
     * "data":[{
     *      "address_id":1055,
     *      "consignee":"等奖",
     *      "mobile":"15181112455",
     *      "address":"啊实打实的",
     *      "is_default":1,
     *      "p_cn":"北京市",
     *       "c_cn":"北京市",
     *      "d_cn":"东城区",
     *      "s_cn":null
     * }]}
     * * //错误返回结果
     * {
     * "status": 301,
     * "msg": "暂无地址信息",
     * "data": false
     * }
     */
    public function addressList()
    {
        if(!Request::instance()->isPost()){
            return $this->getResult(301, 'error', '请求方式有误');
        }
        $user_id = $this->get_user_id();
        if(!$user_id||is_array($user_id)){
            return $this->failResult("用户不存在");
        }
        // $user_id = 42;  //userid为42 测试用
        $useraddr=new UserAddr();
        $addlist=$useraddr->getAddressList(['user_id'=>$user_id]);
        if($addlist){
            return $this->successResult($addlist);
        }else{
            return $this->failResult("暂无地址信息");
        }
    }


}