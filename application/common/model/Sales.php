<?php
/**
*	tpshop
*  ---------------------------------------------------------------------------------------
*	author: pc
*	date: 2019-3-25
**/

namespace app\common\Model;
use app\common\model\Member;
use think\Model;
use think\Db;
use think\Cache;

/**
 * 返佣类逻辑
 * Class Sales
 * @package app\common\logic
 */
class Sales extends Model
{
	private $user_id; //用户id
	private $order_id;//订单id
	private $goods_id;//商品id

	public function __construct($user_id,$order_id,$goods_id)
	{	
		$this->user_id  = $user_id;
		$this->order_id = $order_id;
		$this->goods_id = $goods_id;
	}

	public function sales()
	{
		$user_id = $this->user_id;
		$user    = $this->get_user();
		$bonus_products_id = 0;
		
		if (!$user) {
			return array('msg'=>"该用户不存在",'code'=>0);
		}
		
	}

	/***
	 * state 判断是否是预计收益或者真实收益
	 * num   循环次数
	 * 
	 */


	public function reward_leve($user_id,$order_id,$order_sn,$order_price,$state = 0,$num = 0){

		$num += 1;

		$info  = Member::where(['id' => $user_id])->find();
        
		$first_leader = $info['first_leader'];
		
		if($first_leader <= 0 || $num > 3){
			return true;
		}
		 //获取直推人数
		$leader_count  = Member::getFisrtLeaderNumAttr($first_leader);
	

		$commission    = Db::name('distribution')->where(['level' => $num])->find();
		
		if($leader_count > $commission['start_num'] && $leader_count < $commission['start_end']){
			$money =  $order_price * $commission['levelratio'];
			
			if($state == 1){
				//提现解锁
				$this->cash_unlock($first_leader);
				 //余额 佣金操作 
				 $moneyUpdate = [
					'remainder_money'        => Db::raw('remainder_money+' . $money),
					'distribut_money'        => Db::raw('distribut_money+'. $money),
				];
				$res = Member::where(['id' => $first_leader])->update($moneyUpdate);
				if($res == false){
					return false;
				}

				$balance = [
					'user_id'     => $first_leader,
					'balance'     => $info['remainder_money'] + $money,
					'source_type' => 1,
					'log_type'    => 1,
					'source_id'   => $order_id,
					'bonus_from'  => $num.'级分佣奖励',
					'old_balance' => $info['remainder_money'],
					'note'        => $num.'级分佣奖励',  
					'create_time' => time(),
				];

			  $res	= Db::name('menber_balance_log')->insert($balance);
			  if($res == false){
				return false;
			  }
			}else{
				//预计收益
				$distrbut = [
					'user_id'     => $user_id,
					'to_user_id'  => $first_leader,
					'money'       => $money,
					'order_sn'    => $order_sn,
					'order_id'    => $order_id,
					'num'         => 0,
					'distribut_type'        => 0,
					'status'                => 1,
					'desc'                  => $num.'级分佣奖励',
					'create_time'           => time(),
					'distrbut_state'        => $state,
				]; 
				$res = Db::name('distrbut_commission_log')->insert($distrbut);
				if($res == false){
					return false;
				}
			}

			
		}
		
		$this->reward_leve($first_leader,$order_id,$order_sn,$order_price,$state,$num);
	}


	
	

	
	//订单信息
	public function order()
	{
		
		$order_sn = M('order')->where('order_id',$this->order_id)->value('order_sn');
		
		$order_goods = M('order_goods')
						->where('order_id',$this->order_id)
						->where('goods_id',$this->goods_id)
						->find();
		
		if (!$order_goods) {
			return array('msg'=>"没有该商品的订单信息",'code'=>0);
		}

		$order_goods['order_sn'] = $order_sn;

		return array('data'=>$order_goods,'code'=>1);
	}

	//商品信息
	public function goods($goods_id)
	{
		$goods = M('goods')->where('goods_id',$goods_id)->field('goods_id,shop_price,is_team_prize,prize_ratio')->find();

		if (!$goods) {
			return array('msg'=>"没有该商品的信息",'code'=>0);
		}

		return array('goods'=>$goods,'code'=>1);
	}

	//记录日志
	public function writeLog($data,$divide)
	{
		$bool = M('distrbut_commission_log')->insertAll($data);

		if($divide){
			$order_divide = M('order_divide')->where('user_id',$divide['user_id'])->where('order_id',$divide['order_id'])->where('goods_id',$divide['goods_id'])->find();
			if (!$order_divide) {
				//分钱记录日志
				M('order_divide')->insert($divide);
			} else {
				M('order_divide')->where('user_id',$divide['user_id'])->where('order_id',$divide['order_id'])->where('goods_id',$divide['goods_id'])->setInc('money',$divide['money']);
			}
		}
		
		return $bool;
	}

	/**
	 * 提现解锁
	 */
	public function cash_unlock($parents_id)
	{
		if (!$parents_id) {
			return false;
		}

		Member::where(['id' => $parents_id])->update(['is_cash'=>1]);
	
	}

    //分销成功发消息
    public function distribution_success($openid,$title,$goods_name,$goods_price,$money,$remark,$url=''){
        $time=date("Y-m-d H:i:s",time());

        $data = [
            'touser' => $openid,
            'template_id' => '6kBWThyD-i4OCEMdkQIYSdEEEV-PljfTiOX0T0mjpOg',
            'url' => $url,
            'data' => [
                'first' => [
                    'value' => $title,
                ],
                'keyword1' => [
                    'value' => $goods_name,
                ],
                'keyword2' => [
                    'value' => $goods_price . ' 元',
                ],
                'keyword3' => [
                    'value' => $money . ' 元',
                ],
                'keyword4' => [
                    'value' => $time,
                ],
                'remark' => [
                    'value' => $remark,
                ],
            ],
        ];
        return $this->Send_Template_Message($data);
    }

    # 发送模板消息
    public function Send_Template_Message($data){
        if(!$data){
            return false;
        }
        $conf = Db::name('wx_user')->field('id,appid,appsecret,web_access_token,web_expires')->find();
        $token = $conf['web_access_token'];
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$token;
        $res = httpRequest($url,'POST',json_encode($data));
        return $res;

    }
}