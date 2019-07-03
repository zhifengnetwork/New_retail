<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/20
 * Time: 14:47
 */

namespace app\api\service;

use think\Cache;
use think\cache\driver\Redis;
use think\Db;
use think\Request;
use think\Session;

/**
 * 基础业务类
 * Class BaseService
 */
class  UserService extends BaseService
{

    /**
     * 注册后登录
     * @param $user_name    string 用户名(邮箱或手机号码)
     * @param $user_password string 密码
     * @param $login_type    string 1 手机登录 2邮箱登录
     * @param $verify_code  string 验证码
     * @return $this 对象
     * @author Zgp Create At 2018年11月2日
     */
    public function regLogin($user_name, $user_password, $login_type, $area_code, $phone_system, $phone_model, $ip,$register_from,$version_name,$login_channel,$lang)
    {
        //注册处理
        if (empty($user_name) || empty($user_password) || empty($login_type)) {
            $this->error = F('tb.request_tip_1');
            return $this;
        }
        $userModel = new User();
        //判断名称是否存在
        if ($login_type == FZ::LOGIN_TYPE_PHONE) {
            //手机号码
            $userData = $userModel->selectUserByMobilePhone($user_name);
            if (!empty($userData)) {
                $this->error = F('user.phone_verify_3');
                return $this;
            }
            //插入数据
            $arr = array(
                'phone' => $user_name
            );
        } else {
            //邮箱
            $userData = $userModel->selectUserByEmail($user_name);
            if (!empty($userData)) {
                $this->error = F('user.email_verify_3');
                return $this;
            }
            //插入数据
            $arr = array(
                'email' => $user_name,
                'email_verify' => 1
            );
        }

        $arr['salt'] = $this->setSaltAttr();
        $arr['create_ip'] = $this->setCreateIpAttr();
        $arr['login_ip'] = $this->setLoginIpAttr();
        $arr['password'] = md5($user_password . md5($user_password) . $arr['salt']);
        $arr['area_code'] = !empty($area_code) ? $area_code : '86' ;
        $arr['create_time'] = TIME;

        $userModel = new \app\api\model\User();
        $userModel->startTrans();
        $result = $userModel->insertUser($arr);
        if (!$result) {
            $userModel->rollback();
            $this->error = '写入用户出错';
            return $this;
        }
        $insert_user_id = $userModel->getLastInsID();

        //新增用户信息相关信息
        $userInfoData = [
            'user_id' => $insert_user_id,
            'phone_system' => $phone_system,
            'phone_model' => $phone_model,
            'ip' => request()->ip(),
            'register_from'=>$register_from,
            'create_time' => TIME
        ];
        $result = Db::name('user_info')->insert($userInfoData);
        if (!$result) {
            $userModel->rollback();
            $this->error = '写入用户出错';
            return $this;
        }
        $userAuth = [
            'user_id'=>$insert_user_id,
            'invite_code'=>\app\api\controller\Activity::getInviteCode()
        ];
        $result = Db::name('user_auth')->insert($userAuth);
        if (!$result) {
            $userModel->rollback();
            $this->error = '写入用户出错';
            return $this;
        }

        //新增注册活动--直接派发
        $result = Activity::registerActivity($insert_user_id);
        if ($result >= 2) {
            $userModel->rollback();
            $this->error = '活动出错';
            return $this;
        }
        $userModel->commit();

        //登录处理
        $userData = $userModel->selectUserByUserID($insert_user_id);
        if (!empty($userData['phone'])) {
            $userData['token'] = user_md5($userData['id'] . $userData['phone'] . FZ::LOGIN_TYPE_PHONE . time());
        } else {
            $userData['token'] = user_md5($userData['id'] . $userData['email'] . FZ::LOGIN_TYPE_EMAIL . time());
        }
        $userTokenModel = new \app\api\model\UserToken();
        $userTokenData = $userTokenModel->selectUserTokenByUserID($userData['id']);
        if (!empty($userTokenData)) {
            $before_token = $userTokenData['now_token'];
            //修改用户token记录
            $tokenWhere['user_id'] = $userData['id'];
            $arr = array(
                'now_token' => $userData['token'],
                'before_token' => $before_token,
                'expire_time' => FZ::SESSION_EXPIRE_TIME,
                'login_channel' => $login_channel,
                'create_time' => TIME
            );
            $result = $userTokenModel->updateUserTokenByCriteria($tokenWhere, $arr);
        } else {
            //新增用户token记录
            $arr = array(
                'user_id' => $userData['id'],
                'now_token' => $userData['token'],
                'login_channel' => $login_channel,
                'expire_time' => FZ::SESSION_EXPIRE_TIME,
                'create_time' => TIME
            );
            $result = $userTokenModel->insertUserToken($arr);
            //表示首次登陆 ，显示新手礼包
            $end_time = strtotime(date('Y-m-d',TIME).' 23:59:59') - TIME;
            Cache::set('newprize_'.$userData['id'],$userData['id'],$end_time);
        }
        if ($result) {
            unset($userData['password']);
            unset($userData['fund_password']);
            unset($userData['who_login']);
            unset($userData['lock_login_time']);
            unset($userData['lock_fund_time']);
            unset($userData['salt']);
            unset($userData['google_secret']);

            $userData['login_two'] = $userData['google_type'];//判断是否二次登陆
            $userData['avatar'] = getImagePath($userData['avatar']);//图片
            //获取用户的唯一邀请码
            $userAuth = Db::name('user_auth')->where(['user_id'=>$userData['id']])->field('invite_code')->find();
            $userData['invite_code'] = $userAuth['invite_code'];

            //H5 量化交易大赛的字段
            $data = [
                'user_id' => $userData['id'],
                'type' => 'quant',
                'lang' => $lang,
                'login_channel' => $login_channel,
                'time' => TIME + FZ::SESSION_EXPIRE_TIME
            ];
            $aes = new Aes();
            //base64加密
            $userData['encrypt'] = $aes->aes256cbcEncrypt(serialize($data));
            $this->result = $userData;
        } else {
            $this->error = F('user.login_fail');
        }
        $historyData = [
            'user_id' => $userData['id'],
            'type' => 'login',
            'create_time' => TIME,
            'channel' => $login_channel,
            'value' => request()->ip(),
            'remark' => $userData['phone'] . ';app登录日志' . date('Y-m-d H:i:s')
        ];
        //新增登录日志
        $result = Db::name('user_history_data')->insert($historyData);
        if ($result) {
            $this->result = $userData;
        } else {
            $this->error = F('user.login_fail');
        }

        FailedLog::delLogErrorRecord($userData['id']);

        //add start by zgp  1.3版本不处理卡券提示
        $userData['notice_activity'] = 0; // 1 表示需要弹窗 0 表示不用户弹窗
        $userData['is_show'] = 0; // 0 不显示小红点 1 显示小红点
        $userData['notice_activity_content'] = '';
        //add end by zgp  1.3版本不处理卡券提示

        //新修改的卡券提示逻辑。@author huangxunyue <h88305@qq.com>
//                $userVoucher = new \app\api\model\UserVoucher();
//                $voucherTips = $userVoucher->isLoginTips($userData['id']); //检查是否需要提示

//                $userData['notice_activity'] = $voucherTips['notice_activity']; // 1 表示需要弹窗 0 表示不用户弹窗
//                $userData['is_show'] = $voucherTips['is_show']; // 0 不显示小红点 1 显示小红点
//                $userData['notice_activity_content'] = $voucherTips['notice_activity_content'];
        $this->result = $userData;

        //插入成功的登录日志
        $this->insert_log_login($userData['id'],$user_name,$version_name,$phone_system,$ip,$phone_model,1,$register_from);
        //修改用户语言
        switch ($lang) {
            case 'hk':
                $default_lang = 'zh-hk';
                break;
            case 'en':
                $default_lang = 'en-us';
                break;
            case 'ko':
                $default_lang = 'ko-kr';
                break;
            default:
                $default_lang = 'zh-cn';
        }
        //用户寻找的默认语言
        $userModel = new \app\api\model\User();
        $where = array(
            'id' => $userData['id'],
        );
        $arr = array(
            'default_lang' => $default_lang
        );
        $userModel->updateUserByCriteria($where, $arr);

        //执行异步写入初始化数据
        $newPrize = Db::name('bussiness_config')->where(['name'=>'app_new_prize_cmd'])->find();
            if(!empty($newPrize)){
//            $cmd = 'php E:\www\118\futureapi\think NewPrizeActivity'.' '.$insert_user_id;
            $cmd = $newPrize['value'].' '.$insert_user_id;
            if(substr(php_uname(), 0, 7) == "Windows"){
                pclose(popen("start /B ". $cmd, "r"));
            }else{
                exec($cmd . " > /dev/null &");
            }
        }
        //add by zgp 2019.3.5 如果是谷歌验证码验证登陆，返回参数为空处理
        if($userData['login_two'] == 1){
            $userData['login_key'] = UserAuthKey::createKey($userData['id']);
            $userData['id'] = 0;
            $userData['email'] = '';
            $userData['phone'] = '';
            $userData['user_type'] = 0;
            $userData['real_name_encrypt'] = '';
            $userData['avatar'] = '';
            $userData['token'] = '';
            $userData['invite_code'] = '';
        }else{
            $userData['login_key'] = '';
        }
        //add by zgp 2019.3.5 如果是谷歌验证码验证登陆，返回参数为空处理


        $this->result = $userData;
        return $this;
    }

    /**
     * 用户登录业务处理
     * @param $user_name        string 用户名(邮箱或手机号码)
     * @param $user_password    string 密码
     * @param $login_type       string 1 手机登录 2邮箱登录
     * @param $lang             string 语言
     * @return $this 对象
     * @author Zgp Create At 2018年8月29日
     */
    public function userLogin($user_name, $user_password, $login_type, $login_channel,$version_name,$phone_system,$ip,$phone_model,$register_from,$lang)
    {
        $l = F('app');
        if (empty($user_name) || empty($user_password) || empty($login_type)) {
            $this->error = F('tb.request_tip_1');
            return $this;
        }
        $userModel = new User();
        //判断名称是否存在
        if ($login_type == FZ::LOGIN_TYPE_PHONE) {
            //手机号码
            $userData = $userModel->selectUserByMobilePhone($user_name);
            if(!empty($userData)){
                if ($userData['is_black'] == 1) {
                    //如果是黑名单，不能登录
                    $this->error = $l['account_limit_notuse'];
                    return $this;
                }
            }
        } else {
            //邮箱
            $userData = $userModel->selectUserByEmail($user_name);
            if(!empty($userData)){
                if ($userData['is_black'] == 1) {
                    //如果是黑名单，不能登录
                    $this->error = $l['account_limit_notuse'];
                    return $this;
                }
            }
        }

        //判断过去二十分钟之内是否失败次数超过4次，超过的话返回剩余冻结时间
        $lock_data = \app\api\model\FailedLog::getLockStatus($userData['id']);
        if ($lock_data['count'] >= 4) {
            $this->error = $l['account_is_lock'];
            return $this;
        }elseif ($lock_data['lock_time'] > TIME){
            $this->error = $l['account_is_lock'];
            return $this;
        }
//        if ($lock_data['count'] >= 4) {
//            //插入错误的登录日志
//            $this->insert_log_login($userData['id'],$user_name,$version_name,$phone_system,$ip,$phone_model,4,$register_from);
//            //修改lock_login_time 时间
//            Db::name('user')->where(['id' => $userData['id']])->update(['lock_login_time' => TIME + FZ::LOCK_LOGIN_TIME]);
//            $left = $lock_data['lock_time'] - TIME;
//            ($left > 3600) ? $msg = round(($left / 3600), 0) . F('user.service_tip_5') : $msg = round(($left / 60), 0) . F('user.service_tip_6');
//            $this->error = F('user.service_tip_7') . $lock_data['count'] . F('user.service_tip_8') . $msg . F('user.service_tip_12');
//            return $this;
//        }elseif ($lock_data['lock_time'] > TIME){
//            $left = $lock_data['lock_time'] - TIME;
//            ($left > 3600) ? $msg = round(($left / 3600), 0) . F('user.service_tip_5') : $msg = round(($left / 60), 0) . F('user.service_tip_6');
//            $this->error = F('user.service_tip_13') . $msg . F('user.service_tip_12');
//            return $this;
//        }

        if (!empty($userData)) {
            if (md5($user_password . md5($user_password) . $userData['salt']) == $userData['password']) {
                $userData = $userModel->selectUserByUserID($userData['id']);

                if (!empty($userData['phone'])) {
                    $userData['token'] = user_md5($userData['id'] . $userData['phone'] . FZ::LOGIN_TYPE_PHONE . time());
                } else {
                    $userData['token'] = user_md5($userData['id'] . $userData['email'] . FZ::LOGIN_TYPE_EMAIL . time());
                }
                $userTokenModel = new \app\api\model\UserToken();
                $userTokenData = $userTokenModel->selectUserTokenByUserID($userData['id']);
                if (!empty($userTokenData)) {
                    $before_token = $userTokenData['now_token'];
                    //修改用户token记录
                    $tokenWhere['user_id'] = $userData['id'];
                    $arr = array(
                        'now_token' => $userData['token'],
                        'before_token' => $before_token,
                        'expire_time' => FZ::SESSION_EXPIRE_TIME,
                        'login_channel' => $login_channel,
                        'create_time' => TIME
                    );
                    $result = $userTokenModel->updateUserTokenByCriteria($tokenWhere, $arr);
                } else {
                    //新增用户token记录
                    $arr = array(
                        'user_id' => $userData['id'],
                        'now_token' => $userData['token'],
                        'login_channel' => $login_channel,
                        'expire_time' => FZ::SESSION_EXPIRE_TIME,
                        'create_time' => TIME
                    );
                    $result = $userTokenModel->insertUserToken($arr);
                    //表示首次登陆 ，显示新手礼包
                    $end_time = strtotime(date('Y-m-d',TIME).' 23:59:59') - TIME;
                    Cache::set('newprize_'.$userData['id'],$userData['id'],$end_time);
                }
                if ($result) {
                    unset($userData['password']);
                    unset($userData['fund_password']);
                    unset($userData['who_login']);
                    unset($userData['user_type']);
                    unset($userData['lock_login_time']);
                    unset($userData['lock_fund_time']);
                    unset($userData['salt']);
                    unset($userData['google_secret']);

                    $userData['login_two'] = $userData['google_type'];//判断是否二次登陆
                    $userData['avatar'] = getImagePath($userData['avatar']);//图片
                    //获取用户的唯一邀请码
                    $userAuth = Db::name('user_auth')->where(['user_id'=>$userData['id']])->field('invite_code')->find();
                    $userData['invite_code'] = $userAuth['invite_code'];

                    //H5 量化交易大赛的字段
                    $data = [
                        'user_id' => $userData['id'],
                        'type' => 'quant',
                        'lang' => $lang,
                        'login_channel' => $login_channel,
                        'time' => TIME + FZ::SESSION_EXPIRE_TIME
                    ];
                    $aes = new Aes();
                    //base64加密
                    $userData['encrypt'] = $aes->aes256cbcEncrypt(serialize($data));

                    $this->result = $userData;
                } else {
                    $this->error = F('user.login_fail');
                }
                $historyData = [
                    'user_id' => $userData['id'],
                    'type' => 'login',
                    'create_time' => TIME,
                    'channel' => $login_channel,
                    'value' => request()->ip(),
                    'remark' => $userData['phone'] . ';app登录日志' . date('Y-m-d H:i:s')
                ];
                //新增登录日志
                $result = Db::name('user_history_data')->insert($historyData);
                if ($result) {
                    $this->result = $userData;
                } else {
                    $this->error = F('user.login_fail');
                }
                //登录解锁
                FailedLog::delLogErrorRecord($userData['id']);

                //add start by zgp  1.3版本不处理卡券提示
                $userData['notice_activity'] = 0; // 1 表示需要弹窗 0 表示不用户弹窗
                $userData['is_show'] = 0; // 0 不显示小红点 1 显示小红点
                $userData['notice_activity_content'] = '';
                //add end by zgp  1.3版本不处理卡券提示

                //新修改的卡券提示逻辑。@author huangxunyue <h88305@qq.com>
//                $userVoucher = new \app\api\model\UserVoucher();
//                $voucherTips = $userVoucher->isLoginTips($userData['id']); //检查是否需要提示

//                $userData['notice_activity'] = $voucherTips['notice_activity']; // 1 表示需要弹窗 0 表示不用户弹窗
//                $userData['is_show'] = $voucherTips['is_show']; // 0 不显示小红点 1 显示小红点
//                $userData['notice_activity_content'] = $voucherTips['notice_activity_content'];
                $this->result = $userData;

                // change by hxy 2018年11月9日17:35:14
                // 如果已开启谷歌验证码的则不记录登录成功的日志
                if(!$userData['google_type']){
                    //插入成功的登录日志
                    $this->insert_log_login($userData['id'],$user_name,$version_name,$phone_system,$ip,$phone_model,1,$register_from);
                }
                // change by hxy 2018年11月9日17:35:14

                //修改用户语言
                switch ($lang) {
                    case 'hk':
                        $default_lang = 'zh-hk';
                        break;
                    case 'en':
                        $default_lang = 'en-us';
                        break;
                    case 'ko':
                        $default_lang = 'ko-kr';
                        break;
                    default:
                        $default_lang = 'zh-cn';
                }
                //用户寻找的默认语言
                $userModel = new \app\api\model\User();
                $where = array(
                    'id' => $userData['id'],
                );
                $arr = array(
                    'default_lang' => $default_lang
                );
                $userModel->updateUserByCriteria($where, $arr);

                //add by zgp 2019.3.5 如果是谷歌验证码验证登陆，返回参数为空处理
                if($userData['login_two'] == 1){
                    $userData['login_key'] = UserAuthKey::createKey($userData['id']);
                    $userData['id'] = 0;
                    $userData['email'] = '';
                    $userData['phone'] = '';
                    $userData['user_type'] = 0;
                    $userData['real_name_encrypt'] = '';
                    $userData['avatar'] = '';
                    $userData['token'] = '';
                    $userData['invite_code'] = '';
                }else{
                    $userData['login_key'] = '';
                }
                //add by zgp 2019.3.5 如果是谷歌验证码验证登陆，返回参数为空处理

            } else {
                //插入错误的登录日志
                $this->insert_log_login($userData['id'],$user_name,$version_name,$phone_system,$ip,$phone_model,2,$register_from);

                //插入失败数据，返回剩余次数
                $account = empty($userData['phone']) ? $userData['email'] : $userData['phone'];
                \app\api\model\FailedLog::insertPasswordError($userData['id'], \request()->ip(), $account);
                $lock_data = \app\api\model\FailedLog::getLockStatus($userData['id']);
                if($lock_data['count'] == 1){
                    $this->error = $l['account_error'];
                }elseif($lock_data['count'] >= 4) {
                    $this->error = $l['accout_error_already'];
//                    $this->error = F('user.service_tip_9');

                }else{
                    $this->error = $l['account_count_enter'].(4 - $lock_data['count']) . F('user.service_tip_11');
//                    $this->error = F('user.service_tip_10') . (4 - $lock_data['count']) . F('user.service_tip_11');
                }
                return $this;

            }
        } else {
            $this->error = F('user.not_exist');
        }

        return $this;
    }

    /**
     * 用户注册业务处理
     * @param $user_name    string 用户名(邮箱或手机号码)
     * @param $user_password string 密码
     * @param $login_type    string 1 手机登录 2邮箱登录
     * @param $verify_code  string 验证码
     * @return $this 对象
     * @author Zgp Create At 2018年8月29日
     */
    public function userRegister($user_name, $user_password, $login_type, $verify_code, $area_code, $phone_system, $phone_model, $ip,$register_from)
    {
        if (empty($user_name) || empty($user_password) || empty($login_type)) {
            $this->error = F('tb.request_tip_1');
            return $this;
        }
        $userModel = new User();
        //判断名称是否存在
        if ($login_type == FZ::LOGIN_TYPE_PHONE) {
//            //验证码时间是否超时
            if(empty(Cache::get('expire_time_' . $user_name))){
                $this->error = F('user.service_tip_4');
                return $this;
            }

            if (time() - Cache::get('expire_time_' . $user_name) > FZ::VERIFY_TIME) {
                $this->error = F('user.verify_code_3');
                return $this;
            }
            //验证码是否正确
            $session_verify_code = Cache::get('verify_code_' . $user_name);
            if ($session_verify_code != $verify_code) {
                $this->error = F('user.verify_code_4');
                return $this;
            }
            //中国区号
            if ($area_code == '86') {
                if (!preg_match("/^1[3456789]\d{9}$/", $user_name)) {
                    $this->error = F('user.phone_verify_2');
                    return $this;
                }
            }
            //手机号码
            $userData = $userModel->selectUserByMobilePhone($user_name);
            if (!empty($userData)) {
                $this->error = F('user.phone_verify_3');
                return $this;
            }
            //插入数据
            $arr = array(
                'phone' => $user_name
            );

        } else {
            //验证码时间是否超时
            if (time() - Cache::get('expire_time_' . $user_name) > FZ::VERIFY_TIME) {
                $this->error = F('user.verify_code_3');
                return $this;
            }
            //验证码是否正确
            $session_verify_code = Cache::get('verify_code_' . $user_name);
            if ($session_verify_code != $verify_code) {
                $this->error = F('user.verify_code_4');
                return $this;
            }
            $pregEmail = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
            if (!preg_match($pregEmail, $user_name)) {
                $this->error = F('validate.user_tip_2');
                return $this;
            }
            //邮箱
            $userData = $userModel->selectUserByEmail($user_name);
            if (!empty($userData)) {
                $this->error = F('user.email_verify_3');
                return $this;
            }
            //插入数据
            $arr = array(
                'email' => $user_name,
                'email_verify' => 1
            );
        }

        $arr['salt'] = $this->setSaltAttr();
        $arr['create_ip'] = $this->setCreateIpAttr();
        $arr['login_ip'] = $this->setLoginIpAttr();
        $arr['password'] = md5($user_password . md5($user_password) . $arr['salt']);
        $arr['area_code'] = !empty($area_code) ? $area_code : '86' ;
        $arr['create_time'] = TIME;

        $userModel = new \app\api\model\User();
        $userModel->startTrans();
        $result = $userModel->insertUser($arr);
        if (!$result) {
            $userModel->rollback();
            $this->error = '写入用户出错';
            return $this;
        }
        $insert_user_id = $userModel->getLastInsID();

        //新增用户信息相关信息
        $userInfoData = [
            'user_id' => $insert_user_id,
            'phone_system' => $phone_system,
            'phone_model' => $phone_model,
            'ip' => $ip,
            'register_from'=>$register_from,
            'create_time' => TIME
        ];
        $result = Db::name('user_info')->insert($userInfoData);
        if (!$result) {
            $userModel->rollback();
            $this->error = '写入用户出错';
            return $this;
        }
        $userAuth = [
            'user_id'=>$insert_user_id,
            'invite_code'=>\app\api\controller\Activity::getInviteCode()
        ];
        $result = Db::name('user_auth')->insert($userAuth);
        if (!$result) {
            $userModel->rollback();
            $this->error = '写入用户出错';
            return $this;
        }

        //新增注册活动
        $result = Activity::registerActivity($insert_user_id);
        if ($result >= 2) {
            $userModel->rollback();
            $this->error = '活动出错';
            return $this;
        }

        $userModel->commit();
        $userData = array(
            'user_name' => $user_name,
            'login_type' => $login_type,
            'user_id' => $insert_user_id
        );
        $this->result = $userData;

        //执行异步写入初始化数据
        $newPrize = Db::name('bussiness_config')->where(['name'=>'app_new_prize_cmd'])->find();
        if(!empty($newPrize)){
//            $cmd = 'php E:\www\118\futureapi\think NewPrizeActivity'.' '.$insert_user_id;
            $cmd = $newPrize['value'].' '.$insert_user_id;
            if(substr(php_uname(), 0, 7) == "Windows"){
                pclose(popen("start /B ". $cmd, "r"));
            }else{
                exec($cmd . " > /dev/null &");
            }
        }

        return $this;
    }

    /**
     * 修改用户登录密码业务
     * @param $user_name    string 用户名(邮箱或手机号码)
     * @param $user_password string 密码
     * @param $login_type    string 1 手机登录 2邮箱登录
     * @param $verify_code  string 验证码
     * @return $this    对象
     * @author Zgp Create At 2018年11月6日
     */
    public function resetLoginPassword($user_name, $user_password, $login_type, $verify_code){
        if (empty($user_name) || empty($user_password) || empty($login_type)) {
            $this->error = F('tb.request_tip_1');
            return $this;
        }
        $l = F('app');
        $userModel = new User();
        //判断名称是否存在
        if ($login_type == FZ::LOGIN_TYPE_PHONE) {
            //验证码是否正确
            $session_verify_code = Cache::get('verify_code_' . $user_name);
            if ($session_verify_code != $verify_code) {
                $this->error = $l['verify_error_enter'];
                return $this;
            }
            //手机号码
            $userData = $userModel->selectUserByMobilePhone($user_name);
            if (empty($userData)) {
                $this->error = F('user.phone_verify_4');
                return $this;
            }
        } else {
            //验证码是否正确
            $session_verify_code = Cache::get('verify_code_' . $user_name);
            if ($session_verify_code != $verify_code) {
                $this->error = $l['verify_error_enter'];
                return $this;
            }
            $pregEmail = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
            if (!preg_match($pregEmail, $user_name)) {
                $this->error = F('validate.user_tip_2');
                return $this;
            }
            //邮箱
            $userData = $userModel->selectUserByEmail($user_name);
            if (empty($userData)) {
                $this->error = F('user.email_verify_5');
                return $this;
            }
        }
        //修改用户密码
        $arr = array(
            'password' => md5($user_password . md5($user_password) . $userData['salt'])
        );
        //如果跟旧密码一致情况
        if ($userData['password'] == $arr['password']) {
            $result = 1;
        } else {
            $userModel = new \app\api\model\User();
            $result = $userModel->updateUserByUserID($userData['id'], $arr);
            if (!$result) {
                $this->error = F('user.service_tip_3');
                return $this;
            }
        }
        //通过找回解锁
        FailedLog::delLogErrorRecord($userData['id']);

        $this->result = $result;
        return $this;
    }

    /**
     * 修改用户登录密码业务
     * @param $user_name    string 用户名(邮箱或手机号码)
     * @param $user_password string 密码
     * @param $login_type    string 1 手机登录 2邮箱登录
     * @param $verify_code  string 验证码
     * @return $this    对象
     * @author Zgp Create At 2018年8月29日
     */
    public function editUserLoginPassword($user_name, $user_password, $login_type, $verify_code)
    {
        if (empty($user_name) || empty($user_password) || empty($login_type)) {
            $this->error = F('tb.request_tip_1');
            return $this;
        }
        $userModel = new User();
        //判断名称是否存在
        if ($login_type == FZ::LOGIN_TYPE_PHONE) {
            //验证码时间是否超时
            if (time() - Cache::get('expire_time_' . $user_name) > FZ::VERIFY_TIME) {
                $this->error = F('user.verify_code_3');
                return $this;
            }
            //验证码是否正确
            $session_verify_code = Cache::get('verify_code_' . $user_name);
            if ($session_verify_code != $verify_code) {
                $this->error = F('user.verify_code_4');
                return $this;
            }
            //手机号码
            $userData = $userModel->selectUserByMobilePhone($user_name);
            if (empty($userData)) {
                $this->error = F('user.phone_verify_4');
                return $this;
            }

        } else {
            //验证码时间是否超时
            if (time() - Cache::get('expire_time_' . $user_name) > FZ::VERIFY_TIME) {
                $this->error = F('user.verify_code_3');
                return $this;
            }
            //验证码是否正确
            $session_verify_code = Cache::get('verify_code_' . $user_name);
            if ($session_verify_code != $verify_code) {
                $this->error = F('user.verify_code_4');
                return $this;
            }
            $pregEmail = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
            if (!preg_match($pregEmail, $user_name)) {
                $this->error = F('validate.user_tip_2');
                return $this;
            }
            //邮箱
            $userData = $userModel->selectUserByEmail($user_name);
            if (empty($userData)) {
                $this->error = F('user.email_verify_5');
                return $this;
            }
        }
        //修改用户密码
        $arr = array(
            'password' => md5($user_password . md5($user_password) . $userData['salt'])
        );
        //如果跟旧密码一致情况
        if ($userData['password'] == $arr['password']) {
            $result = 1;
        } else {
            $userModel = new \app\api\model\User();
            $result = $userModel->updateUserByUserID($userData['id'], $arr);
            if (!$result) {
                $this->error = F('user.service_tip_3');
                return $this;
            }
        }
        //通过找回解锁
        FailedLog::delLogErrorRecord($userData['id']);
        //add by zgp 2019.3.5 找回密码后，重新登陆
        Db::name('user_token')->where(['user_id'=>$userData['id']])->update(['expire_time'=>1]);
        //add by zgp 20193.5  找回密码后，重新登陆
        $this->result = $result;
        return $this;
    }

    /**
     * user表字段salt获取
     * @return string
     * @author Zgp Create At 2018年8月29日
     */
    public function setSaltAttr()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $salt = '';
        for ($i = 0; $i < 6; $i++) {
            $salt .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $salt;
    }

    /**
     * 获取注册ip地址
     * @return string ip地址
     * @author Zgp Create At 2018年8月29日
     */
    protected function setCreateIpAttr()
    {
        return request()->ip();
    }

    /**
     * 获取登录IP地址
     * @return string ip地址
     * @author Zgp Create At 2018年8月29日
     */
    protected function setLoginIpAttr()
    {
        return request()->ip();
    }

}