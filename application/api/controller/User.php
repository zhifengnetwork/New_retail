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
     * @apiParam {string}    temp      发送模板类型注册（sms_reg）（必填）
     * @apiParam {string}    auth      校验规则（md5(phone+temp)）（必填）
     * @apiParam {string}    type      默认1（必填）
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
     * @api {POST} /user/sharePoster 我的分享
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
        $result = [];
        try {
            if (!Request::instance()->isPost()) return $this->getResult(301, 'error', '请求方式有误');
            $user_id = get_user_id();
            if(!$user_id){
                return $this->failResult('用户不存在', 301);
            }
            $share_error = 0;
            $filename = $user_id.'-qrcode.png';
            $save_dir = ROOT_PATH.'public/shareposter/';
            $my_poster = $save_dir.$user_id.'-share.png';
            $my_poster_src = SITE_URL.'/shareposter/'.$user_id.'-share.png';
            if( !file_exists($my_poster) ){
                    $imgUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/index.php?dfc5b='.$user_id;
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
            $data['my_poster_src'] = $my_poster_src;
            return $this->successResult($data);
    } catch (Exception $e) {
            $result = $this->failResult($e->getMessage(), 301);
    }
           return $result;
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
        $user_id = get_user_id();
        if(!$user_id){
            return $this->failResult('用户不存在', 301);
        }
        //佣金总收益
        $distribut_money  = Db::name('member')->where('id',$user_id)->value('distribut_money');
        //团队人数
        $team_count        = Db::query("SELECT count(*) as count FROM parents_cache where find_in_set('$user_id',`parents`)");
        $data['estimate_money']  = 0.01;//预计收入
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
        $where['to_user_id'] = $user_id;
        $list = Db::name('distrbut_commission_log')->where($where)->field('order_sn,money,desc')->paginate(20,false,['page'=>$page]);
     
        $data['list'] = $list;
        
        return $this->successResult($list);
    }






}