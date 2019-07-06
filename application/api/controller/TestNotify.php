<?php
namespace app\api\controller;
use Payment\Notify\PayNotifyInterface;
use Payment\Config;
use think\Loader;
use think\Db;

/**
 * @author: helei
 * @createTime: 2016-07-20 18:31
 * @description:
 */

/**
 * 客户端需要继承该接口，并实现这个方法，在其中实现对应的业务逻辑
 * Class TestNotify
 * anthor helei
 */
class TestNotify implements PayNotifyInterface
{
    public function notifyProcess(array $data)
    {
        $channel = $data['channel'];
        if ($channel === Config::ALI_CHARGE){// 支付宝支付
        } elseif ($channel === Config::WX_CHARGE) {// 微信支付
             //修改订单状态
             $update = [
                'seller_id'      => $data['seller_id'],
                'transaction_id' => $data['transaction_id'],
                'order_status'   => 1,
                'pay_status'     => 1,
                'pay_time'       => strtotime($data['pay_time']),
            ];

            Db::startTrans();

            Db::name('order')->where(['order_sn' => $data['order_no']])->update($update);

            $order = Db::table('order')->where(['order_sn' => $data['order_no']])->field('order_id,groupon_id,user_id')->find();

            $goods_res = Db::table('order_goods')->field('goods_id,goods_name,goods_num,spec_key_name,goods_price,sku_id')->where('order_id',$order['order_id'])->select();
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
                    $jg = sprintf("%.2f",$value['goods_price'] * $value['goods_num']);
                    $jifen = sprintf("%.2f",$jifen + ($jg * $goods['gift_points']));
                }else{
                    $goods['gift_points'] = $goods['gift_points'] ? $goods['gift_points'] : 0;
                    $jifen = sprintf("%.2f",$jifen + ($value['goods_num'] * $goods['gift_points']));
                }
            }
           
            $res = Db::table('member')->update(['id'=>$order['user_id'],'gouwujifen'=>$jifen]);
            if($res == false){
                Db::rollback();
                return false;
            }
            $Sales = new Sales($user_id,$order_id,0);

            $rest = $Sales->reward_leve($user_id,$order_id,$order['order_sn'],0);
            
            if($rest == false){
                Db::rollback();
                return false;
            }

            Db::commit();
            return true;

        } elseif ($channel === Config::CMB_CHARGE) {// 招商支付
        } elseif ($channel === Config::CMB_BIND) {// 招商签约
        } else {
            // 其它类型的通知
        }
        // 执行业务逻辑，成功后返回true
        return true;
    }
}