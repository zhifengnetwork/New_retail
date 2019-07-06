<?php
/**
 * 50元专区
 */
namespace app\api\controller;
use app\common\controller\ApiBase;
use think\Db;
use think\Config;

class FiftyZone extends ApiBase
{
    public function shop_list(){
        $user_id = $this->get_user_id();

        $user = Db::table('member')->field('release,residue_release,release_ci,release_time')->find($user_id);

        $time = strtotime(date("Y-m-d"),time());

        if(!$user['residue_release'] && $user['release'] == $user['release_ci'] && $user['release_time'] == $time ){
            $this->ajaxReturn(['status' => 301 , 'msg'=>'今天发布次数已用完！','data'=>'']);
        }
        
        if( (!$user['residue_release'] && $time > $user['release_time']) || !$user['residue_release'] ){
            $this->ajaxReturn(['status' => 302 , 'msg'=>'是否缴纳30元服务费！','data'=>'']);
        }

        $goods_id = 50;
        $info = Db::table('config')->where('module',5)->column('value','name');
        if(isset($info['ailicode'])) $info['ailicode'] = Config('c_pub.apiimg') . $info['ailicode'];
        if(isset($info['wechatcode'])) $info['wechatcode'] = Config('c_pub.apiimg') . $info['wechatcode'];
        if(isset($info['mayuncode'])) $info['mayuncode'] = Config('c_pub.apiimg') . $info['mayuncode'];
        if($info['default_type']==1) $info['default_pay_code'] = $info['ailicode'];
        if($info['default_type']==2) $info['default_pay_code'] = $info['wechatcode'];
        if($info['default_type']==3) $info['default_pay_code'] = $info['mayuncode'];

        $where['g.goods_id'] = $goods_id;
        $where['gi.main'] = 1;
        $where['fzs.stock'] = ['neq',0];

        $list = Db::table('fifty_zone_shop')->alias('fzs')
                ->join('goods g','g.goods_id=fzs.goods_id','LEFT')
                ->join('goods_img gi','gi.goods_id=g.goods_id','LEFT')
                ->join('member m','m.id=fzs.user_id','LEFT')
                ->field('fzs.*,g.goods_name,gi.picture img')
                ->where($where)
                ->order('fzs.add_time DESC')
                ->limit($info['show_num'])
                ->select();

        if(empty($list)){
            $arr = [];
            for($i=0;$i<20;$i++){
                $arr[$i]['user_id'] = 0;
                $arr[$i]['goods_id'] = $goods_id;
                $arr[$i]['stock'] = 11;
                $arr[$i]['frozen_stock'] = 0;
                $arr[$i]['add_time'] = time();
            }
            Db::table('fifty_zone_shop')->insertAll($arr);
            $list = Db::table('fifty_zone_shop')->alias('fzs')
                ->join('goods g','g.goods_id=fzs.goods_id','LEFT')
                ->join('goods_img gi','gi.goods_id=g.goods_id','LEFT')
                ->join('member m','m.id=fzs.user_id','LEFT')
                ->field('fzs.*,g.goods_name,gi.picture img')
                ->where($where)
                ->order('fzs.add_time DESC')
                ->limit($info['show_num'])
                ->select();
        }

        foreach($list as $key=>&$value){
            $value['img'] = Config('c_pub.apiimg') . $value['img'];
            if(!$value['user_id']){
                $value['mobile'] = $info['shop_mobile'];
                $value['pay_code'] = $info['default_pay_code'];
            }
        }
        
        $this->ajaxReturn(['status' => 200 , 'msg'=>'成功！','data'=>$list]);
    }
    
    public function get_release(){
        $user_id = $this->get_user_id();

        $data = Db::table('member')->where('id',$user_id)->field('remainder_money,pwd')->find();
        if(!$data['pwd']){
            $data['pwd'] = 0;
        }else{
            $data['pwd'] = 1;
        }

        $data['release_money'] = Db::table('config')->where('module',5)->where('name','release_money')->value('value');

        $pay = Db::table('sysset')->value('sets');
        $pay = unserialize($pay)['pay'];

        $pay_type = Config('PAY_TYPE');
        $arr = [];
        $i = 0;
        foreach($pay as $key=>$value){
            
            if($value){
                if( $pay_type[$key]['pay_type'] == 4 ) continue;
                $arr[$i]['pay_type'] = $pay_type[$key]['pay_type'];
                $arr[$i]['pay_name'] = $pay_type[$key]['pay_name'];
                $i++;
            }
        }

        $data['pay_type'] = $arr;

        $this->ajaxReturn(['status' => 200 , 'msg'=>'成功！','data'=>$data]);
    }

    

}
