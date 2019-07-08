<?php

/***
 * 支付api
 */
namespace app\api\controller;
use app\api\controller\TestNotify;
use app\common\controller\ApiBase;
use Payment\Common\PayException;
use Payment\Notify\PayNotifyInterface;
use Payment\Notify\AliNotify;
use Payment\Client\Charge;
use Payment\Client\Notify;
use Payment\Config as PayConfig;
use app\common\model\Member as MemberModel;
use app\common\model\Order;

use \think\Model;
use \think\Config;
use \think\Db;
use \think\Env;
use \think\Request;

class Pay extends ApiBase
{
     /**
     * 析构流函数
     */
    public function  __construct() {           
        require_once ROOT_PATH.'vendor/riverslei/payment/autoload.php';
    }    




    
      /**
     * @api {POST} /pay/set_payment 设置收款
     * @apiGroup user
     * @apiVersion 1.0.0
     *
     * @apiParamExample {json} 请求数据:
     * {
     *     "token":"",           token
     *      "type":''            当前操作的类型 1:码云  2:微信   3:支付宝
     *      "name":""              昵称
     *      "image":""           收款码图片
     *     
     *          
     *     操作对应的类型,填写对应的昵称和图片路径
     * }
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     * {"status":200,"msg":"success","data":"操作成功"}
     * //错误返回结果
     * {"status":301,"msg":"fail","data":"操作失败"}
     * 无
     */
    public function set_payment()
    {
        $userid=$this->get_user_id();
        // $userid=42;
        if(!Request()->isPost()){
            return $this->failResult("请求方式错误");
        }
        $type=input('type');
        if(!$type){
            return $this->failResult("没有选择收款方式");
        }

        $data=input();

        if(empty($data['name'])){    //提供name
            return $this->failResult("缺少姓名参数");
        }
                                  
        $imgPath=uploadOne('image');    //提供image

        if(!$imgPath){
            return $this->failResult('缺少图片参数');
        }
        $imgPath=DS.'uploads'. DS . 'pay_picture\\'.$imgPath;
        if($type==1){
                $data['my_name']=$data['name'];
                $data['my_pic']=$imgPath;
                $data['pay_default']=3;
                $data['my_status']=1;
        }
        if($type==2){
                $data['wx_pic']=$imgPath;
                $data['wx_name']=$data['name'];
                $data['pay_default']=2;
                $data['wx_status']=1;
        }
        if($type==3){
                $data['zfb_pic']=$imgPath;
                $data['zfb_account']=$data['name'];
                $data['pay_default']=1;
                $data['zfb_status']=1;
        }

        $data['user_id']=$userid;
        $data['create_time']=time();
        unset($data['type']);
        unset($data['name']);
        $one=Db::name('member_payment')->where('user_id',$userid)->find();
        if($one){
            $res=Db::name("member_payment")->where('user_id',$userid)->update($data);
        }else{
            $res=Db::name("member_payment")->insert($data);
        }
        if($res){
            return $this->successResult("操作成功");
        }else{
            return $this->failResult("操作失败");
        }

    }




    /***
     * 支付
     */
    public function payment(){
        $order_id     = input('order_id');
        $pay_type     = input('pay_type');//支付方式
        $user_id      = $this->get_user_id();

        $order_info   = Db::name('order')->where(['order_id' => $order_id])->field('order_id,groupon_id,order_sn,order_amount,pay_type,pay_status,user_id')->find();//订单信息
        if($order_info){
            //从订单列表立即付款进来
            $pay_type     = $order_info['pay_type'];//支付方式
        }
        $member       = MemberModel::get($user_id);
        //验证是否本人的
        if(!$order_info){
            $this->ajaxReturn(['status' => 301 , 'msg'=>'订单不存在','data'=>'']);
        }
        if($order_info['user_id'] != $user_id){
            $this->ajaxReturn(['status' => 301 , 'msg'=>'非本人订单','data'=>'']);
        }

    	if($order_info['pay_status'] == 1){
			$this->ajaxReturn(['status' => 301 , 'msg'=>'此订单，已完成支付!','data'=>'']);
        }
        $amount       = $order_info['order_amount'];
        $client_ip    = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
       
        if($pay_type == 2){
            $payData = [
                'body'        => 'test body',
                'subject'     => 'test subject',
                'order_no'    =>  $order_info['order_sn'],
                'timeout_express' => time() + 600,// 表示必须 600s 内付款
                'amount'       => $amount,// 金额
                'return_param' => '',
                'client_ip'    => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',// 客户地址
                'scene_info' => [
                    'type'     => 'Wap',// IOS  Android  Wap  腾讯建议 IOS  ANDROID 采用app支付
                    'wap_url'  => 'https://helei112g.github.io/',//自己的 wap 地址
                    'wap_name' => '微信支付',
                ],
            ];
            $wxConfig = Config::get('wx_config');
            $url      = Charge::run(Config::WX_CHANNEL_WAP, $wxConfig, $payData);
            try {
                $this->ajaxReturn(['status' => 200 , 'msg'=>'支付路径','data'=> ['url' => $url]]);
            } catch (PayException $e) {
                $this->ajaxReturn(['status' => 301 , 'msg'=>$e->errorMessage(),'data'=>'']);
                exit;
            }
        }elseif($pay_type == 1){
            // $balance_info  = get_balance($user_id,0);
            if($member['remainder_money'] < $order_info['order_amount']){
                $this->ajaxReturn(['status' => 301 , 'msg'=>'余额不足','data'=>'']);
            }

            // 启动事务
            Db::startTrans();

            //扣除用户余额
            $remainder_money = [
                'remainder_money'            =>  Db::raw('remainder_money-'.$amount.''),
            ];
            $res =  Db::table('member')->where(['user_id' => $user_id])->update($remainder_money);
            if(!$res){
                Db::rollback();
            }

            //余额记录
            $balance_log = [
                'user_id'      => $user_id,
                'balance'      => $balance_info['balance'] - $order_info['order_amount'],
                'balance_type' => $balance_info['balance_type'],
                'source_type'  => 0,
                'log_type'     => 0,
                'source_id'    => $order_info['order_sn'],
                'note'         => '商品订单消费',
                'create_time'  => time(),
                'old_balance'  => $balance_info['balance']
            ];
            $res2 = Db::table('menber_balance_log')->insert($balance_log);
            if(!$res2){
                Db::rollback();
            }
            //修改订单状态
            $update = [
                'order_status' => 1,
                'pay_status'   => 1,
                'pay_type'     => $pay_type,
                'pay_time'     => time(),
            ];
            $reult = Order::where(['order_id' => $order_id])->update($update);

            $goods_res = Db::table('order_goods')->field('goods_id,goods_name,goods_num,spec_key_name,goods_price,sku_id')->where('order_id',$order_id)->select();
            $jifen = 0;
            foreach($goods_res as $key=>$value){

                $goods = Db::table('goods')->where('goods_id',$value['goods_id'])->field('less_stock_type,gift_points')->find();
                //付款减库存
                if($goods['less_stock_type']==2){
                    Db::table('goods_sku')->where('sku_id',$value['sku_id'])->setDec('inventory',$value['goods_num']);
                    Db::table('goods_sku')->where('sku_id',$value['sku_id'])->setDec('frozen_stock',$value['goods_num']);
                    Db::table('goods')->where('goods_id',$value['goods_id'])->setDec('stock',$value['goods_num']);
                }
                $baifenbi = strpos($goods['gift_points'] ,'%');
                if($baifenbi){
                    $goods['gift_points'] = substr($goods['gift_points'],0,strlen($goods['gift_points'])-1); 
                    $goods['gift_points'] = $goods['gift_points'] / 100;
                    $jg    = sprintf("%.2f",$value['goods_price'] * $value['goods_num']);
                    $jifen = sprintf("%.2f",$jifen + ($jg * $goods['gift_points']));
                }else{
                    $goods['gift_points'] = $goods['gift_points'] ? $goods['gift_points'] : 0;
                    $jifen = sprintf("%.2f",$jifen + ($value['goods_num'] * $goods['gift_points']));
                }
            }
           
            $res = Db::table('member')->update(['id'=>$user_id,'gouwujifen'=>$jifen]);

            if($reult){
                // 提交事务
                Db::commit();
                $this->ajaxReturn(['status' => 200 , 'msg'=>'余额支付成功!','data'=>['order_id' =>$order_info['order_id'],'order_amount' =>$order_info['order_amount'],'goods_name' => getPayBody($order_info['order_id']),'order_sn' => $order_info['order_sn'] ]]);
            }else{
                 Db::rollback();
                $this->ajaxReturn(['status' => 301 , 'msg'=>'余额支付失败','data'=>'']);
            }
        }
       
    }

    public function release_wx_pay(){
        $user_id = $this->get_user_id();
        $pay_type     = input('pay_type');//支付方式
        $pwd = input('pwd');

        $ss = Db::table('config')->where('module',5)->where('name','release_money')->value('value');

        $user = MemberModel::where('id',$user_id)->field('release,residue_release,release_ci,release_time,remainder_money')->find();
        
        if(!$user['residue_release'] && $user['release'] == $user['release_ci'] ){
            $this->ajaxReturn(['status' => 301 , 'msg'=>'今天发布次数已用完！','data'=>'']);
        }

        $time = strtotime(date("Y-m-d"),time());
        if( !$user['residue_release'] && $time > $user['release_time'] && $user['release'] != $user['release_ci'] ){
            MemberModel::where('id',$user_id)->update(['release_time'=>$time,'release_ci'=>0]);
        }

        if($user['residue_release']){
            $this->ajaxReturn(['status' => 301 , 'msg'=>'今天发布次数还未用！','data'=>'']);
        }

        if($pay_type==1){
            $pwd = md5($user['salt'] . $pwd);
            if ($pwd != $user['pwd']) {
                $this->ajaxReturn(['status' => 301 , 'msg'=>'支付密码错误！','data'=>'']);
            }

            if($user['remainder_money'] < $ss){
                $this->ajaxReturn(['status' => 301 , 'msg'=>'余额不足！','data'=>'']);
            }

            // 启动事务
            Db::startTrans();

            $res = Db::table('member')->where('id',$user_id)->setDec('remainder_money',$ss);
            $res1 = Db::table('member')->where('id',$user_id)->setInc('residue_release');

            if($res && $res1){
                //记录
                $log['user_id'] = $user_id;
                $log['balance'] = $ss;
                $log['source_type'] = 5;
                $log['log_type'] = 0;
                $log['source_id'] = $ss;
                $log['note'] = '发布费用';
                $log['create_time'] = time();

                Db::table('menber_balance_log')->insert($log);

                Db::commit();
                $this->ajaxReturn(['status' => 200 , 'msg'=>'付款成功!','data'=>'']);
            }
            Db::rollback();
            $this->ajaxReturn(['status' => 301 , 'msg'=>'付款失败!','data'=>'']);
        }else{
            $orderNo = time() . rand(1000, 9999);
            $payData = [
                'body'        => 'test body',
                'subject'     => 'test subject',
                'order_no'    =>  $orderNo,
                'timeout_express' => time() + 600,// 表示必须 600s 内付款
                'amount'       => $ss,// 金额
                'return_param' => '',
                'client_ip'    => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',// 客户地址
                'scene_info' => [
                    'type'     => 'Wap',// IOS  Android  Wap  腾讯建议 IOS  ANDROID 采用app支付
                    'wap_url'  => 'https://helei112g.github.io/',//自己的 wap 地址
                    'wap_name' => '微信支付',
                ],
            ];
            $wxConfig = Config::get('wx_config');
            $url      = Charge::run(Config::WX_CHANNEL_WAP, $wxConfig, $payData);
            try {
                $this->ajaxReturn(['status' => 200 , 'msg'=>'支付路径','data'=> ['url' => $url]]);
            } catch (PayException $e) {
                $this->ajaxReturn(['status' => 301 , 'msg'=>$e->errorMessage(),'data'=>'']);
                exit;
            }

        }
        
    }
    


    /***
     * 微信支付回调
     */
    public function weixin_notify(){
        $callback = new TestNotify();
        $config   = Config::get('pay_config');
        $ret      = Notify::run('wx_charge', $config, $callback);
        echo  $ret;
    }

}
