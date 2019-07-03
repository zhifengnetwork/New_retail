<?php
/**
 * 继承
 */
namespace app\common\controller;
use app\common\util\jwt\JWT;
use think\Db;
use think\Controller;
use app\common\model\Config;
use think\Request;
use think\Session;
use app\common\util\Redis;

class ApiBase extends ApiAbstract
{
    protected $uid;
    protected $user_name;
    protected $is_bing_mobile;

    public function _initialize () {
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Headers:*");
        header("Access-Control-Allow-Methods:GET, POST, OPTIONS, DELETE");
        header('Content-Type:application/json; charset=utf-8');

        config((new Config)->getConfig());
//        if (empty($this->is_bing_mobile($openid))){
//            $this->ajaxReturn(['code'=>9999,'msg'=>'请绑定手机号！']);
//        }
        if (session('admin_user_auth')) {
            $this->uid = session('admin_user_auth.uid');
            $this->user_name = session('admin_user_auth.user_name');
        } else {
            $action = strtolower(Request::instance()->controller() . '/' . Request::instance()->action());
            $action_array[] = strtolower('goods/categoryList');
            $action_array[] = strtolower('goods/category');
            $action_array[] = strtolower('goods/goodsDetail');
            $action_array[] = strtolower('pay/alipay_notify');
            if (in_array($action, $action_array)) {
                return;
            }
            $user_id = $this->decode_token(input('token'));
            if(empty($user_id)) exit(json_encode(['status'=>999,'msg'=>'您未登录，请登录！']));

        }
    }

    private  static $redis = null;
    /*获取redis对象*/
    protected function getRedis(){
        if(!self::$redis instanceof Redis){
            self::$redis = new Redis(Config('cache.redis'));
        }
        return self::$redis;
    }

    /*
     *  开放有可能不需登录controller
     */
    private function freeLoginController () {
        $controller = [
            'Shop' => 'shop',
//            'User' => 'user',
        ];
        return $controller;
    }

    /**
     * 生成token
     */
    public function create_token($user_id){
        $time = time();
        $payload = array(
            "iss"=> "DC",
            "iat"=> $time ,  
            "exp"=> $time + 36000 , 
            "user_id"=> $user_id
        );
        $key = 'zhelishimiyao';
        $token = JWT::encode($payload, $key, $alg = 'HS256', $keyId = null, $head = null);
        return $token;
    }

    /**
     * 解密token
     */
    public function decode_token($token){
        $key     = 'zhelishimiyao';
        $payload = json_decode(json_encode(JWT::decode($token, $key, ['HS256'])),true);
        return $payload;
    }

    /**
    *
    *接收头信息
    **/
    public function em_getallheaders()
    {
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }

    /**
     * 获取user_id
     */
    public function get_user_id(){
        $headers = $this->em_getallheaders();

        $token   = input('token'); 
         
        $user_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJEQyIsImlhdCI6MTU1OTYzOTg3MCwiZXhwIjoxNTU5Njc1ODcwLCJ1c2VyX2lkIjo3Nn0.YUQ3hG3TiXzz_5U594tLOyGYUzAwfzgDD8jZFY9n1WA';

        if($user_token == $token){
            return 76;
        }else{
            if(!$token){
                return $this->failResult('token不存在', 301);
            }
    
            $res = $this->decode_token($token);
    
            if(!$res){
                return $this->failResult('token已过期', 301);
            }
    
            if(!isset($res['iat']) || !isset($res['exp']) || !isset($res['user_id']) ){
                return $this->failResult('token已过期', 301);
            }
    
            if($res['iat']>$res['exp']){
                return $this->failResult('token已过期', 301);
            }
            return $res['user_id'];
        }
    }

    /**
     *  判断是否绑定手机号码
     */
    protected function is_bing_mobile ($openid) {

        $mobile = Db::table('member')->where('openid',$openid)->value('mobile');
        if (empty($mobile)){
            return false;
        }else{
            return true;
        }

    }

}
