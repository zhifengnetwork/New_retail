<?php
/**
 * Created by PhpStorm.
 * User: zgp
 * Date: 2019/7/2 0002
 * Time: 17:51
 */

namespace app\api\controller;

use app\common\controller\ApiBase;
use app\common\model\Users;
use app\common\logic\UsersLogic;
use app\common\util\jwt\JWT;
use think\Config;
use think\Db;
use think\Exception;
use think\Request;
use app\api\model\UserAddr;
use think\Cache;

class Address extends ApiBase
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
//        if(!Request::instance()->isPost()){
//            return $this->getResult(301, 'error', '请求方式有误');
//        }

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



    /**
     * @api {POST} /address/addAddress 地址添加和编辑
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    token                  token*（必填）
     * @apiParam {string}    address_id             address_id（选填,没有为添加模式）
     * @apiParam {string}    consignee              客户名称（必填）
     * @apiParam {string}    district               区id（必填）
     * @apiParam {string}    address               地址（必填）
     * @apiParam {string}    mobile               地址（必填）
     * @apiParam {string}    is_default              默认地址（必填）
     * @apiParamExample {json} 请求数据:
     * {
     *      "token":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "address_id":"xxxxxxxx",
     *      "consignee"："xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "district"："xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "address"："xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "mobile"："xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "is_default"："xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {"status":200,
     * "msg":"添加成功",
     * "data":[]
     * }
     * * //错误返回结果
     * {
     * "status": 301,
     * "msg": "操作失败",
     * "data": false
     * }
     */
    public function addAddress()
    {
        if(!Request::instance()->isPost()){
            return $this->failResult("请求方式错误");
        }
        $data=input('post.');
        // $user_id=42;//测试
        $user_id=$this->get_user_id();
       
        if(!$user_id||is_array($user_id)){
            return $this->failResult("用户不存在");
        }
        $useraddr=new UserAddr();
        return $useraddr->add_address($user_id,$address_id=0,$data);
    }

    
    /**
     * @api {POST} /address/delAddress 地址删除
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    token                  token*（必填）
     * @apiParam {string}    id                     地址id（必填）
   
     * @apiParamExample {json} 请求数据:
     * {
     *      "token":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "id":"xxxxxxxx",
     *  
     *      
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {"status":200,
     * "msg":"删除成功",
     * "data":[]
     * }
     * * //错误返回结果
     * {
     * "status": 301,
     * "msg": "操作失败",
     * "data": false
     * }
     */
    public function delAddress()
    {
        if(!Request::instance()->isPost()){
            return $this->failResult("请求方式错误");
        }        
        $id=input('post.id');
        $one=Db::name("user_address")->where("address_id",$id)->find();
        if(!$id||is_null($one)){
            return $this->failResult("传入参数无效");
        }
        $res=Db::name("user_address")->where("address_id",$id)->delete();
        if($res){
           return $this->successResult("删除成功");
        }else{
            return $this->failResult("操作失败");
        }
    }

       /**
     * @api {GET} /address/get_region 获取地区下级
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    code                     地址code（选填  如果没有参数会返回所有的省份）
   
     * @apiParamExample {json} 请求数据:
     * {
     *      "code":"xxxxxxxx",
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {"status":200,
     * "msg":"success",
     * "data":[{"area_id":3,"code":"1101","parent_id":"11","area_name":"北京市","area_type":2}]
     * }
     * * //错误返回结果
     * {
     * "status": 301,
     * "msg": "没有数据",
     * "data": false
     * }
     */
    public function get_region(){
        $codeid=input('get.code');
        if(is_null($codeid)||empty($codeid)){
            $res=Db::name('region')->where('parent_id',1)->select();
        }else{
            $res=Db::name('region')->where('parent_id',$codeid)->select();
        }
        if($res){
            return $this->successResult($res);
        }else{
            return $this->failResult('没有数据');
        }
    }

}