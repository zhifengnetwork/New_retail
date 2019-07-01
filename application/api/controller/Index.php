<?php
/**
 * 用户API
 */
namespace app\api\controller;
use app\common\model\Users;
use app\common\logic\UsersLogic;
use think\Db;

class Index extends ApiBase
{


   /**
    * 首页接口
    */
    /**
     * @apiDefine userLoginStatus 必须用户登录.
     * 用户必须在登录之后才能访问
     *
     * @apiVersion 1.0.0
     */
    /**
     * @apiDefine Page
     * @apiParam {int}    page        当前页*（必填）
     * @apiParam {int}    size        每页行数*（必填）
     */
    public function index()
    {
        // $redis = $this->getRedis();
        // for($i=1;$i<=10;$i++){
        //     $redis->rpush('ss',1);
        // }

        // $n = 12;
        // for($i=0;$i<$n;$i++){
        //     if( $redis->lpop('ss') <= 0 ){
        //         for($j=1;$j<=$i;$j++){
        //             $redis->rpush('ss',1);
        //             continue;
        //         }
        //         echo "还有{$i}件可购买";die;
        //         continue;
        //     }
        // }


        // die;
        echo 1111;
        exit;
        $user_id = $this->get_user_id();
        if(!$user_id){
            $this->ajaxReturn(['status' => -1 , 'msg'=>'用户不存在','data'=>'']);
        }


        $data = '首页数据';
        

        $this->ajaxReturn(['status' => 0 , 'msg'=>'获取成功','data'=>$data]);
    }

    /**
     * @api {POST} /api/index/sendRegisterCode 获取验证码
     * @apiGroup index
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    user_name        用户名(邮箱或手机号码)*（必填）
     * @apiParam {string}    login_type        1 手机 2邮箱（必填）
     * @apiParam {string}    area_code        当login_type为1时，区号（必填）
     * @apiParamExample {json} 请求数据:
     * {
     *      "user_name":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "area_code":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
     * }
     * @apiSuccessExample {json} 返回数据：
     * {
     * "status": 200,
     * "msg": "验证码发送成功",
     * "data": ''
     * }
     */
    public function sendRegisterCode(){
        return $this->successResult('验证码发送成功');
    }

    /**
     * @api {POST} /api/index/userInfo 用户信息
     * @apiGroup index
     * @apiVersion 1.0.0
     *
     * @apiParam {int}    user_id        用户ID*（必填）
     * @apiParamExample {json} 请求数据:
     * {
     *      "user_id":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     * }
     * @apiSuccessExample {json} 返回数据：
     * {
     * "status": 200,
     * "msg": "验证码发送成功",
     * "data": ''
     * }
     */
    public function userInfo(){
        return $this->successResult(['user_id'=>1,'user_name'=>'test']);
    }

    /***
     * 首页ID
     */
    public function page(){
        // $user_id = $this->get_user_id();
        // if(!$user_id){
        //     $this->ajaxReturn(['status' => -1 , 'msg'=>'用户不存在','data'=>'']);
        // }
        $ewei = Db::name('diy_ewei_shop')->where(['status' => 1])->find();

        $this->ajaxReturn(['status' => 1 , 'msg'=>'获取首页成功！','data'=>$ewei['id']]);
    }

    

    

    
}
