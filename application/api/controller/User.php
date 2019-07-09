<?php
/**
 * Created by PhpStorm.
 * User: zgp
 * Date: 2019/7/2 0002
 * Time: 17:51
 */

namespace app\api\controller;

use app\common\controller\ApiBase;
use app\common\model\Member;
use app\common\util\jwt\JWT;
use think\Config;
use think\Db;
use think\Exception;
use think\Request;

class User extends ApiBase
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
            // if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
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
     * +---------------------------------
     * 地址组件原数据
     * +---------------------------------
    */
    public function get_address(){
        $user_id = $this->get_user_id();
        if(!$user_id){
            $this->ajaxReturn(['status' => -2 , 'msg'=>'用户不存在','data'=>'']);
        }
        //第一种方法
        //$province_list  =  Db::name('region')->field('*')->where(['area_type' => 1])->column('area_id,area_name');
        // $city_list      =  Db::name('region')->field('*')->where(['area_type' => 2])->column('area_id,area_name');
        // $county_list    =  Db::name('region')->field('*')->where(['area_type' => 3])->column('area_id,area_name');
        // $data = [
        //     'province_list' => $province_list,
        //     'city_list'     => $city_list,
        //     'county_list'   => $county_list,
        // ];
        //第二种方法
        $list  = Db::name('region')->field('*')->select();
        foreach($list as $v){
           if($v['area_type'] == 1){
              $address_list['province_list'][$v['code'] * 10000]=  $v['area_name'];
           }
           if($v['area_type'] == 2){
              $address_list['city_list'][$v['code'] *100]=  $v['area_name'];
           }
           if($v['area_type'] == 3){
              $address_list['county_list'][$v['code']]=  $v['area_name'];
           }
        }
        $this->ajaxReturn(['status'=>200,'msg'=>'获取地址成功','data'=>$address_list]);
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
            $password    = input('user_password/s', '');
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
                $info = Db::table('member')->where('id', $uid)->find();
                if (!$info) {
                    return $this->failResult('邀请人账号不存在！', 301);
                }
               //绑定上下级关系
               $info = Member::where(['user_id' => $uid])->find();
               $insert['first_leader']   = $uid;
               $insert['second_leader']  = $info['first_leader']; //  第一级推荐人
               $insert['third_leader']   = $info['second_leader']; // 第二级推荐人
            }else{
               $insert['first_leader']   = 0;
            }
            //验证码判断
            $res = $this->phoneAuth($phone, $verify_code);
            if ($res === -1) {
                return $this->failResult('验证码已过期！', 301);
            } else if (!$res) {
                return $this->failResult('验证码错误！', 301);
            }
            $salt     = create_salt();
            $password = md5($salt . $password);
            $insert['mobile']     = $phone;
            $insert['salt']       = $salt;
            $insert['password']   = $password;
            $insert['createtime'] = time();
            $id = Db::table('member')->insertGetId($insert);
            if (!$id) {
                return $this->failResult('注册失败，请重试！', 301);
            }
            $data['token']   = $this->create_token($id);
            $data['mobile']  = $phone;
            $data['id']      = $id;
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
     *      "type":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "verify_code":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "user_password":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *      "confirm_password":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {
     * "status": 200,
     * "msg": "success",
     * "data": "修改成功"
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

            if ($password != $confirm_password) {
                return $this->failResult('密码不一致错误', 301);
            }

            if (!preg_match("/^1[23456789]\d{9}$/", $phone)) {
                return $this->failResult('手机号码格式有误', 301);
            }

            $result = $this->validate($this->param, 'User.find_login_password');
            if (true !== $result) {
                return $this->failResult($result, 301);
            }

            $member = Db::name('member')->where(['mobile' => $phone])->field('id,password,pwd,mobile,salt')->find();
            if (empty($member)) {
                return $this->failResult('手机号码不存在', 301);
            }

            //验证码判断
            $res = $this->phoneAuth($phone, $verify_code);
            if ($res === -1) {
                return $this->failResult('验证码已过期！', 301);
            } else if (!$res) {
                return $this->failResult('验证码错误！', 301);
            }

            if ($type == 1) {
                $stri = 'password';
            } else {
                $stri = 'pwd';
            }
            $password = md5($member['salt'] . $password);
            if ($password == $member[$stri]) {
                return $this->failResult('新密码和旧密码不能相同', 301);
            } else {
                $data = array($stri => $password);
                $update = Db::name('member')->where('id', $member['id'])->data($data)->update();
                if ($update) {
                    return $this->successResult('修改成功');
                } else {
                    return $this->failResult('修改失败');
                }
            }

        } catch (Exception $e) {
            $result = $this->failResult($e->getMessage(), 301);
        }
        return $result;
    }

       /**
     * @api {POST} /user/edit_name 修改用户名
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    token              token*（必填）
     * @apiParam {string}    realname              姓名*（必填）
     * @apiParamExample {json} 请求数据:
     * {
     *      "realname":"xxxxxxxxxxxxxxxxxxxxxx",
     *      "token":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {
     * "status": 200,
     * "msg": "success",
     * "data":"成功"
     * 
     * }
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "操作失败",
     * "data": false
     * }
     */
    public function edit_name()
    {
        $user_id = $this->get_user_id();
        $data['realname']=input("realname");
        $memberRes=Db::name("member")->where("id",$user_id)->update($data);
       if($memberRes!==false){
           return $this->successResult("用户名修改成功");
       }else{
           return $this->successResult("操作失败");
       }
    }




       /**
     * @api {POST} /user/team 我的团队
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
     * {
     * "status": 200,
     * "msg": "success",
     * "data": {
     * "team_count": "12",  团队人数
     * "distribut_money": 12.20 佣金总收益
     * "estimate_money": "20.00",  预计收益
     * }
     * }
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "验证码错误！",
     * "data": false
     * }
     */
    public function team()
    {
        if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
        $user_id = $this->get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        //佣金总收益
        $distribut_money    = Db::name('member')->where('id',$user_id)->value('distribut_money');
        //团队人数
        $team_count         = Db::query("SELECT count(*) as count FROM parents_cache where find_in_set('$user_id',`parents`)");
        //预计收益
        $estimate_money     = Db::name('distrbut_commission_log')->where(['to_user_id' => $user_id,'distrbut_state' => 0])->field('sum(money) as money')->find();
        $data['estimate_money']  = $estimate_money['money'];//预计收入
        $data['distribut_money'] = $distribut_money;
        $data['team_count']      = $team_count[0]['count'] ? $team_count[0]['count'] : 0;
        return $this->successResult($data);
    }
      /**
     * @api {POST} /user/team_list 团队列表
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
     * {
     * "status": 200,
     * "msg": "success",
     * "data":[
     * {
     *       "id":1, 用户ID
     *       "realname":"凉", 用户名称
     *        "mobile":"13413695347" 手机号
     * },
     * {
     *       "id":2,
     *       "realname":"啦啦啦",
     *       "mobile":"13413695348"
     * },
     * 
     * ]
     * }
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "验证码错误！",
     * "data": false
     * }
     */
    public function team_list()
    {
        if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
        $user_id = $this->get_user_id();
    
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
     
        $all_lower = get_all_lower($user_id);
       
        $all_lower = implode(',',$all_lower);
        $list = array();
    
        if ($all_lower) {
            $list = Db::query("select id,realname,mobile from `member` where `first_leader` > 0 and `id` in ($all_lower)");
        } 
        $data['list'] = $list;
      
        return $this->successResult($list);
    }


      /**
     * @api {POST} /user/distribut_list 佣金明细
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    token              token*（必填）
     * @apiParam {string}    page              页数*（必填）
     * @apiParamExample {json} 请求数据:
     * {
     *      "page":"1"  页数 默认1,
     *      "token":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {
     * "status": 200,
     * "msg": "success",
     * "data":[
     * {
     *       "order_sn":'RC20190116110509664542', 订单号
     *       "money":"8.00", 金额
     *       "desc":"经理1级别利润(家用1台)" 描述
     * },
     * {
     *       "order_sn":'RC20190116110509282892',
     *       "money":"8.00",
     *       "desc":"总监2级别利润(家用1台)"
     * },
     * 
     * ]
     * }
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "验证码错误！",
     * "data": false
     * }
     */
    public function distribut_list()
    {
        if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
        
        $user_id = $this->get_user_id();
        $page    = input('page',1);
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        $where['distrbut_state'] = 1;
        $where['to_user_id']     = $user_id;
        $list = Db::name('distrbut_commission_log')
        ->where($where)
        ->field('order_sn,money,desc')
        ->paginate(20,false,['page'=>$page]);
     
        $data['list'] = $list;
        
        return $this->successResult($list);
    }


     /**
     * @api {POST} /user/estimate_list 预计收益明细
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParam {string}    token              token*（必填）
     * @apiParam {string}    page              页数*（必填）
     * @apiParamExample {json} 请求数据:
     * {
     *      "page":"1"  页数 默认1,
     *      "token":"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {
     * "status": 200,
     * "msg": "success",
     * "data":[
     * {
     *       "user_id"  : 123132, 用户ID
     *       "realname":'张三', 用户名称
     *       "order_sn":'RC20190116110509664542', 订单号
     *       "money":"8.00", 金额
     * },
     * {
     *      "user_id"  : 123132, 用户ID
     *       "realname":'张三', 
     *       "order_sn":'RC20190116110509282892',
     *       "money":"8.00",
     * },
     * 
     * ]
     * }
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "验证码错误！",
     * "data": false
     * }
     */
    public function estimate_list()
    {
        if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
        
        $user_id = $this->get_user_id();
        $page    = input('page',1);
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        
        $where['to_user_id']     = $user_id;
        $where['distrbut_state'] = 0;
        $list = Db::name('distrbut_commission_log')->alias('d')
                ->join('member m','m.id=d.user_id','LEFT')
                ->where($where)
                ->field('d.user_id,d.order_sn,d.money,m.realname')
                ->paginate(20,false,['page'=>$page]);
     
        $data['list'] = $list;
        
        return $this->successResult($list);
    }


         /**
     * @api {POST} /user/user_info 我的信息
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
     * {
     * "status": 200,
     * "msg": "success",
     * "data": {
     * "id": "12",  用户id
     * "mobile": 12.20 手机号
     * "realname": "20.00", 用户名称
     * "remainder_money": "20.00",  余额
     * "distribut_money": "20.00",  佣金累计收益
     * "estimate_money": "20.00",   预计收益
     * "createtime": "",  注册时间
     * "avatar": "",  头像
     * "collection": "20",  收藏
     * "not_pay" : 0 ,待付款
     * "not_delivery" : 0 ,待发货
     * "not_receiving" : 0 ,待收货
     * "not_evaluate" : 0 ,待评价
     * "refund" : 0 ,退款
     * 
     * }
     * }
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "验证码错误！",
     * "data": false
     * }
     */
    public function user_info()
    {
        if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
        $user_id = $this->get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        $info     = Db::name('member')->where(['id' => $user_id])->field('realname,mobile,id,remainder_money,distribut_money,createtime,avatar')->find();
        //退款
        $refund   = Db::name('order_refund')->where(['user_id' => $user_id,'refund_status' => 2])->field('*')->count();
        //待付款
        $not_pay  = Db::name('order')->where(['user_id' => $user_id,'pay_status' => 0])->field('*')->count();
        //待发货
        $not_delivery   = Db::name('order')->where(['user_id' => $user_id,'pay_status' =>1,'shipping_status' => 0])->field('*')->count();
        //待收货
        $not_receiving  = Db::name('order')->where(['user_id' => $user_id,'pay_status' =>1,'shipping_status' => 1])->field('*')->count();
        //待评价
        $not_evaluate   = Db::name('order')->where(['user_id' => $user_id,'comment' =>0,'pay_status' => 1,'shipping_status' => 3])->field('*')->count();
        //收藏
        $collection     = Db::name('collection')->where(['user_id' => $user_id])->field('*')->count();
        //预计收益
        $estimate_money = Db::name('distrbut_commission_log')->where(['to_user_id' => $user_id,'distrbut_state' => 0])->field('sum(money) as money')->find();
        
        $info['estimate_money'] = $estimate_money['money']; //预计收益
        $info['refund']         = $refund;
        $info['not_pay']        = $not_pay;
        $info['not_delivery']   = $not_delivery;
        $info['not_receiving']  = $not_receiving;
        $info['not_evaluate']   = $not_evaluate;
        $info['collection']     = $collection;


        return $this->successResult($info);
    }

    /**
     * 获取默认头像
     * @param $user_id
     */
    public function defaultTou($user_id){
        $info     = Db::name('member')->where(['id' => $user_id])->field('realname,mobile,id,avatar')->find();
        //默认头像的位置
        return $info['avatar'];
    }

    /**
     * 个人资料页面
     */
    public function personal(){
        $user_id = $this->get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        $info  = Db::name('member')->where(['id' => $user_id])->field('realname,mobile,id,avatar')->find();
        return $this->successResult($info);
    }

   /**
     * 上传头像
     * @throws Exception
     */
    public function updateTou(){
        $user_id = $this->get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        $image = input('image');
        $saveName = request()->time().rand(0,99999) . '.png';
        $imga=file_get_contents($image);
        //生成文件夹
        $names = "tou" ;
        $name = "tou/" .date('Ymd',time()) ;
        if (!file_exists(ROOT_PATH .Config('c_pub.img').$names)){ 
            mkdir(ROOT_PATH .Config('c_pub.img').$names,0777,true);
        }
        file_put_contents(ROOT_PATH .Config('c_pub.img').$name.$saveName,$imga);
        $imgPath = Config('c_pub.apiimg') . $name.$saveName;
        $data['avatar']=$imgPath;
        $member=Db::name('member')->where('id',$user_id)->find();
        if($member){
            $res=Db::name("member")->where('id',$user_id)->update($data);
        }else{
            return $this->failResult("操作失败");
        }
        if($res){
            return $this->successResult("操作成功");
        }else{
            return $this->failResult("操作失败");
        }
    }

    /**
     * 退出成功
     */
    public function logout(){
        $user_id = $this->get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        $data['token'] = '';
        return $this->successResult($data);
    }

    /**
     * 我的邀请链接
     */
    public function shareUrl(){
        $user_id = $this->get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        $info  = Db::name('member')->where(['id' => $user_id])->field('realname,mobile,id,avatar')->find();
        $data['realname'] = $info['realname'];
        $data['avatar'] = $info['avatar'];
        $data['mobile'] = $info['mobile'];
        $data['id'] = $info['id'];
        $data['url'] = '?uid='.$info['id'];
        return $this->successResult($data);
    }

     /**
     * 账户余额
     */
    public function user_remainder(){
        $user_id = $this->get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        $info  = Db::name('member')->where(['id' => $user_id])->field('remainder_money')->find();
        $data['remainder_money'] = $info['remainder_money'];
        return $this->successResult($data);
    }

     /**
     * 提现列表
     */
    public function withdrawal_list(){
        $user_id = $this->get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }

        $list  = Db::name('withdraw')->where(['user_id' => $user_id,'status' => ['neq',-2]])->field('*')->select();
        $data['list'] = $list;
        return $this->successResult($data);
    }

     /**
     * 账单列表
     */
    public function remainder_list(){
        $user_id = $this->get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }

        $list  = Db::name('menber_balance_log')->where(['user_id' => $user_id,'balance_type' => 1])->field('note,balance,source_id,create_time')->select();
        $data['list'] = $list;
        return $this->successResult($data);
    }

      /**
     * 支付宝账号详情
     */
    public function zfb_info(){
        $user_id = $this->get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        $data  = Db::name('menber')->where(['user_id' => $user_id])->field('alipay_name,alipay')->find();
        return $this->successResult($data);
    }


    /**
     * 支付宝账号编辑
     */
    public function zfb_edit(){
        $user_id = $this->get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        $alipay_name = input('alipay_name');
        $alipay      = input('alipay');

        if(empty($alipay_name) || empty($alipay) ){
            return $this->failResult('支付宝用户名或者账户不能为空', 301);
        }

        $data = [
            'alipay'     => $alipay,
            'alipay_name' => $alipay_name,
        ];
        $res  = Db::name('menber')->where(['id' => $user_id])->update($data);

        if($res == false){
            return $this->failResult('编辑失败', 301);
        }
        return $this->successResult($data);
    }



        /**
     * @api {POST} /user/sharePoster 我的推广码
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
     * {
     * "status": 200,
     * "msg": "success",
     * "data": {
     * "my_poster_src": "http:\/\/127.0.0.1:20019\/shareposter\/123-share.png",  图片路径
     * }
     * }
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "验证码错误！",
     * "data": false
     * }
     */
    public function sharePoster(){
        try {
            if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
            $user_id = $this->get_user_id();
            if(!$user_id){
                return $this->failResult('用户不存在', 301);
            }
            $share_error = 0;
            $filename = $user_id.'-qrcode.png';
            $save_dir = ROOT_PATH.'public/shareposter/';
            $my_poster = $save_dir.$user_id.'-share.png';
            $my_poster_src = SITE_URL.'/shareposter/'.$user_id.'-share.png';
            if( !file_exists($my_poster) ){
                    $qianurl = 'http://new_retail_web.zhifengwangluo.com';
                    $imgUrl  = $qianurl.'?uid='.$user_id;
                    vendor('phpqrcode.phpqrcode');
                    \QRcode::png($imgUrl, $save_dir.$filename, QR_ECLEVEL_M);
                    $image_path =  ROOT_PATH.'public/shareposter/load/qr_backgroup.png';
                    if(!file_exists($image_path)){
                        $share_error = 1;
                    }
                    # 分享海报
                    if(!file_exists($my_poster) && !$share_error){
                        # 海报配置
                        $conf = Db::name('config')->where(['name' => 'shareposter'])->find();
                        $config = json_decode($conf['value'],true);
                        $image_w = $config['w'] ? $config['w'] : 75;
                        $image_h = $config['h'] ? $config['h'] : 75;
                        $image_x = $config['x'] ? $config['x'] : 0;
                        $image_y = $config['y'] ? $config['y'] : 0;
                        # 根据设置的尺寸，生成缓存二维码
                        $qr_image = \think\Image::open($save_dir.$filename);
                        $qrcode_temp_path = $save_dir.$user_id.'-poster.png';
                        $qr_image->thumb($image_w,$image_h,\think\Image::THUMB_SOUTHEAST)->save($qrcode_temp_path);
                        
                        if($image_x > 0 || $image_y > 0){
                            $water = [$image_x, $image_y];
                        }else{
                            $water = 5;
                        }
                        # 图片合成
                        $image = \think\Image::open($image_path);
                        $image->water($qrcode_temp_path, $water)->save($my_poster);
                        @unlink($qrcode_temp_path);
                        @unlink($save_dir.$filename);
                    }
            }
            $info  = Db::name('member')->where(['id' => $user_id])->field('realname,mobile,id,avatar')->find();
            $data['realname'] = $info['realname'];
            $data['avatar']   = $info['avatar'];
            $data['mobile']   = $info['mobile'];
            $data['id']       = $info['id'];
            $data['my_poster_src'] = $my_poster_src;
            return $this->successResult($data);
        } catch (Exception $e) {
                return $this->failResult($e->getMessage(), 301);
        }
         
    }



}