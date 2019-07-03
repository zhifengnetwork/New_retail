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

class User extends ApiAbstract
{


    /**
     * @api {POST} /user/login 用户登录
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    phone              手机号码*（必填）
     * @apiParam {string}    user_password      用户密码（必填）
     * @apiParamExample {json} 请求数据:
     * {
     *      "phone":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "user_password":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {
     * "status": 200,
     * "msg": "success",
     * "data": {
     * "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJEQyIsImlhdCI6MTU2MjEyNDc0NCwiZXhwIjoxNTYyMTYwNzQ0LCJ1c2VyX2lkIjoiODAifQ.y_TRtHQ347Hl3URRJ4ECVgPbyGbniwyGyHjSjJY7fXY",  token值，下次调用接口，需传回给后端
     * "mobile": "18520783339",     手机号码
     * "id": "80"       用户ID
     * }
     * }
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "手机号码格式有误！",
     * "data": false
     * }
     */
    public function login()
    {
        $result = [];
        try {
            if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
            $phone = trim($this->param['phone']);
            $password = trim($this->param['user_password']);

            $result = $this->validate($this->param, 'User.login');
            if (true !== $result) {
                return $this->failResult($result, 301);
            }

            if (!preg_match("/^1[23456789]\d{9}$/", $phone)) {
                return $this->failResult('手机号码格式有误', 301);
            }

            $data = Db::table("member")->where('mobile', $phone)
                ->field('id,password,mobile,salt')
                ->find();

            if (!$data) {
                return $this->failResult('手机不存在或错误', 301);
            }

            $password = md5($data['salt'] . $password);

            if ($password != $data['password']) {
                return $this->failResult('登录密码错误', 301);
            }

            unset($data['password'], $data['salt']);
            //重写
            $data['token'] = $this->create_token($data['id']);
            $result = $this->successResult($data);

        } catch (Exception $e) {
            $result = $this->failResult($e->getMessage(), 301);
        }
        return $result;
    }

    /**
     * @api {POST} /user/sendVerifyCode 发送验证码
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    phone              手机号码*（必填）
     * @apiParam {string}    temp      发送模板类型：注册 sms_reg；忘记密码 sms_forget（必填）
     * @apiParam {string}    auth      校验规则（md5(phone+temp)）（必填）
     * @apiParam {string}    type      1登录密码 2支付密码（必填）
     * @apiParamExample {json} 请求数据:
     * {
     *      "phone":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "user_password":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {
     * "status": 200,
     * "msg": "success",
     * "data": "发送成功！"
     * }
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "手机号码格式有误！",
     * "data": false
     * }
     */
    public function sendVerifyCode()
    {
        $result = [];
        try {
            if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
            $phone = input('post.phone/s', '');
            $temp = input('post.temp/s', '');
            $auth = input('post.auth/s', '');
            $type = input('type/d', 1);
            $result = $this->sendPhoneCode($phone, $temp, $auth, $type);
            if ($result['status'] == 1) {
                return $this->successResult($result['msg']);
            }

            return $this->failResult($result['msg'], 301);

        } catch (Exception $e) {
            $result = $this->failResult($e->getMessage(), 301);
        }
        return $result;
    }

    /**
     * @api {POST} /user/register 用户注册
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    phone              手机号码*（必填）
     * @apiParam {string}    verify_code        验证码（必填）
     * @apiParam {string}    user_password      用户密码（必填）
     * @apiParam {string}    confirm_password   用户确认密码（必填）
     * @apiParamExample {json} 请求数据:
     * {
     *      "phone":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "verify_code":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "user_password":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "confirm_password":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {
     * "status": 200,
     * "msg": "success",
     * "data": {
     * "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJEQyIsImlhdCI6MTU2MjEyNDc0NCwiZXhwIjoxNTYyMTYwNzQ0LCJ1c2VyX2lkIjoiODAifQ.y_TRtHQ347Hl3URRJ4ECVgPbyGbniwyGyHjSjJY7fXY",   token值，下次调用接口，需传回给后端
     * "mobile": "18520783339",     手机号码
     * "id": "80"       用户ID
     * }
     * }
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "验证码错误！",
     * "data": false
     * }
     */
    public function register()
    {
        $result = [];
        try {
            if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
            $phone = input('phone/s', '');
            $verify_code = input('verify_code/s', '');
            $password = input('user_password/s', '');
            $confirm_password = input('confirm_password/s', '');
            $uid = input('uid', 0);
            if ($password != $confirm_password) {
                return $this->failResult('密码不一致');
            }

            $result = $this->validate($this->param, 'User.register_phone');
            if (true !== $result) {
                return $this->failResult($result, 301);
            }

            $member = Db::table('member')->where('mobile', $phone)->value('id');

            if ($member) {
                return $this->failResult('此手机号已注册，请直接登录！', 301);
            }
            if ($uid) {
                $uid = Db::table('member')->where('mobile', $phone)->value('id');
                if (!$uid) {
                    return $this->failResult('邀请人账号不存在！', 301);
                }
            }
            //验证码判断
            $res = $this->phoneAuth($phone, $verify_code);
            if ($res === -1) {
                return $this->failResult('验证码已过期！', 301);
            } else if (!$res) {
                return $this->failResult('验证码错误！', 301);
            }

            $agenttime = 0;
            $agentid = 0;
            if ($uid) {
                $agentid = $uid;
                $agenttime = time();
            }
            $salt = create_salt();
            $password = md5($salt . $password);

            $id = Db::table('member')->insertGetId(['mobile' => $phone, 'uid' => $uid, 'agentid' => $agentid, 'agenttime' => $agenttime, 'isagent' => 1, 'salt' => $salt, 'password' => $password, 'createtime' => time()]);
            if (!$id) {
                return $this->failResult('注册失败，请重试！', 301);
            }

            $data['token'] = $this->create_token($id);
            $data['mobile'] = $phone;
            $data['id'] = $id;
            return $this->successResult($data);
        } catch (Exception $e) {
            $result = $this->failResult($e->getMessage(), 301);
        }
        return $result;
    }

    /**
     * @api {POST} /user/resetPassword 修改密码
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    phone              手机号码*（必填）
     * @apiParam {string}    type               1 登录密码；2 支付密码*（必填）
     * @apiParam {string}    verify_code        验证码（必填）
     * @apiParam {string}    user_password      用户密码（必填）
     * @apiParam {string}    confirm_password   用户确认密码（必填）
     * @apiParamExample {json} 请求数据:
     * {
     *      "phone":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "verify_code":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "user_password":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "confirm_password":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {
     * "status": 200,
     * "msg": "success",
     * "data": {
     * "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJEQyIsImlhdCI6MTU2MjEyNDc0NCwiZXhwIjoxNTYyMTYwNzQ0LCJ1c2VyX2lkIjoiODAifQ.y_TRtHQ347Hl3URRJ4ECVgPbyGbniwyGyHjSjJY7fXY",   token值，下次调用接口，需传回给后端
     * "mobile": "18520783339",     手机号码
     * "id": "80"       用户ID
     * }
     * }
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "验证码错误！",
     * "data": false
     * }
     */
    public function resetPassword()
    {
        $result = [];
        try {
            if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
            $phone = input('phone/s', '');
            $type = input('type', 1);
            $verify_code = input('verify_code/s', '');
            $password = input('user_password/s', '');
            $confirm_password = input('confirm_password/s', '');

            if($password != $confirm_password){
                return $this->failResult('密码不一致错误',301);
            }

            if (!preg_match("/^1[23456789]\d{9}$/", $phone)) {
                return $this->failResult('手机号码格式有误', 301);
            }

            $result = $this->validate($this->param, 'User.find_login_password');
            if (true !== $result) {
                return $this->failResult($result, 301);
            }

            $member = Db::name('member')->where(['mobile' => $phone])->field('id,password,pwd,mobile,salt')->find();
            if(empty($member)){
                return $this->failResult('手机号码不存在',301);
            }

            //验证码判断
            $res = $this->phoneAuth($phone, $verify_code);
            if ($res === -1) {
                return $this->failResult('验证码已过期！', 301);
            } else if (!$res) {
                return $this->failResult('验证码错误！', 301);
            }

            if($type == 1 ){
                $stri = 'password';
            }else{
                $stri = 'pwd';
            }
            $password = md5($member['salt'] . $password);
            if ($password == $member[$stri]){
                return $this->failResult('新密码和旧密码不能相同',301);
            }else{
                $data = array($stri=>$password);
                $update = Db::name('member')->where('id',$member['id'])->data($data)->update();
                if($update){
                    return $this->successResult('修改成功');
                }else{
                    return $this->failResult('修改失败');
                }
            }

        } catch (Exception $e) {
            $result = $this->failResult($e->getMessage(), 301);
        }
        return $result;
    }
}