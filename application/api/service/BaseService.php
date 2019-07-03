<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/20
 * Time: 14:47
 */

namespace app\api\service;

use app\common\util\FZ;
use think\Cache;
use think\cache\driver\Redis;
use think\Db;

/**
 * 基础业务类
 * Class BaseService
 */
class  BaseService
{

    public $data;
    public $result = [];
    public $error;
    public $code;


    /**
     * 判断用户是否已经登录
     * @param $user_id      用户ID
     * @param $user_name    用户名称
     * @param $login_type   登录类型
     * @return $this    对象
     * @author Zgp Create At 2018年8月29日
     */
    public function checkLogin($user_id, $user_name, $login_type)
    {
        switch ($login_type) {
            case FZ::LOGIN_TYPE_PHONE:
                //查询用户是否存在
                $userModel = new \app\api\model\User();
                $data = $userModel->selectUserByMobilePhone($user_name);
                if (empty($data)) {
                    $this->error = F('user.not_exist');
                } else {
                    //查询token是否过期
                    $token = user_md5($user_id . $user_name . $login_type);
                    $userTokenModel = new \app\api\model\UserToken();
                    $where['user_id'] = $user_id;
                    $where['token'] = $token;
                    $data = $userTokenModel->selectUserTokenByCriteria($where);
                    if (empty($data)) {
                        $this->error = F('api.base_tip_8');
                    }
                }
                break;
            case FZ::LOGIN_TYPE_EMAIL:
                //查询用户是否存在
                //查询token是否过期
                $this->error = F('api.base_tip_9');
                break;
        }
        return $this;

    }

    /**
     * 新增后台用户登录日志
     * @param $user_id  用户ID
     * @param $name     用户名称
     * @param $version_name 版本号
     * @param $phone_system 操作系统
     * @param $ip           ip地址
     * @param $phone_model  手机型号
     * @param $state    //状态,1为登录成功,2为密码错误,3.登出,4.锁定,5.谷歌验证码错误,6谷歌验证码冻结
     * @param $register_from    包来源
     * @return int|string
     * @author Zgp Create At 2018年8月29日
     */
    public function insert_log_login($user_id,$name,$version_name,$phone_system,$ip,$phone_model,$state,$register_from){
        $ip = request()->ip();
        $insert = [
            'name'=>$name,
            'user_id'=>$user_id,
            'user_type'=>1,//'用户类型,1为会员账号,2为后台管理员账号',
            'ip_address'=>$ip,
            'user_model'=>$phone_model,
            'user_system'=>$phone_system,
            'system_version'=>$version_name,
            'state'=>$state,
            'package_from'=>$register_from,
            'create_time'=>TIME
        ];
        return Db::name('log_login')->insert($insert);
    }

}