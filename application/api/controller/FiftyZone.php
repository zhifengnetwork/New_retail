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

        if($this->fifty_order(1)){
            $this->ajaxReturn(['status' => 304 , 'msg'=>'还有未付款的订单！','data'=>'']);
        }

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
        $where['fzs.user_id'] = ['neq',$user_id];

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

    public function fiftySubmitOrder(){
        $user_id = $this->get_user_id();

        $user = Db::table('member')->field('release,residue_release,release_ci,release_time')->find($user_id);

        $time = strtotime(date("Y-m-d"),time());

        if(!$user['residue_release'] && $user['release'] == $user['release_ci'] && $user['release_time'] == $time ){
            $this->ajaxReturn(['status' => 301 , 'msg'=>'今天发布次数已用完！','data'=>'']);
        }
        
        if( (!$user['residue_release'] && $time > $user['release_time']) || !$user['residue_release'] ){
            $this->ajaxReturn(['status' => 302 , 'msg'=>'是否缴纳30元服务费！','data'=>'']);
        }        

        $info = Db::table('config')->where('module',5)->column('value','name');

        $num = 10;
        
        $fz_ids = input('fz_id');

        if(!$fz_ids){
            $this->ajaxReturn(['status' => 301 , 'msg'=>'请选择商品！','data'=>'']);
        }
        $fz_id = explode(',',$fz_ids);

        if( count($fz_id) != $num ){
            $this->ajaxReturn(['status' => 301 , 'msg'=>"请选择{$num}商品！",'data'=>'']);
        }

        $list = Db::table('fifty_zone_shop')->where('fz_id','in',$fz_ids)->field('fz_id,goods_id,user_id,stock')->select();

        if(!$list){
            $this->ajaxReturn(['status' => 301 , 'msg'=>"请选择商品！",'data'=>'']);
        }

        
        $arr = [];
        $time = time();
        // $res2 = Db::table('order')->insertGetId(['order_sn'=>date('YmdHis',$time) . mt_rand(10000000,99999999),'user_id'=>$user_id,'prom_type'=>5,'add_time'=>$time]);
        
        foreach($list as $key=>$value){
            if($value['user_id'] == $user_id){
                Db::commit();
                $this->ajaxReturn(['status' => 301 , 'msg'=>"不能购买自己的商品！",'data'=>'']);
            }
            if(!$value['stock']){
                Db::commit();
                $this->ajaxReturn(['status' => 301 , 'msg'=>"个别商家的商品已没库存，请刷新列表！",'data'=>'']);
            }
            $arr[$key]['fz_id']        = $value['fz_id'];
            // $arr[$key]['order_id']     = $res2;
            $arr[$key]['order_sn']     = date('YmdHis',$time) . mt_rand(10000000,99999999);
            $arr[$key]['user_id']      = $user_id;
            $arr[$key]['shop_user_id'] = $value['user_id'];
            $arr[$key]['goods_id']     = $value['goods_id'];
            $arr[$key]['goods_num']    = 1;
            $arr[$key]['add_time']     = $time;
            $arr[$key]['sure_time']    = $time + ($info['auto_cancel'] * 60);
        }

        
        //开启事务
        Db::startTrans();
        
        $res = Db::table('fifty_zone_order')->insertAll($arr);

        if($res){
            foreach($arr as $key=>$value){
                if( !Db::table('fifty_zone_shop')->where('fz_id',$value['fz_id'])->setDec('stock') ){
                    Db::rollback();
                    $this->ajaxReturn(['status' => 301 , 'msg'=>"系统错误！",'data'=>'']);
                }
            }

            $res1 = Db::table('fifty_zone_shop')->insert(['user_id'=>$user_id,'goods_id'=>$arr[0]['goods_id'],'stock'=>count($arr)+1,'add_time'=>$time]);

            Db::table('member')->where('id',$user_id)->setDec('residue_release');
            Db::table('member')->where('id',$user_id)->setInc('release_ci');

            if($res1){
                Db::commit();
                $this->ajaxReturn(['status' => 200 , 'msg'=>"成功！",'data'=>'']);
            }

            Db::rollback();
            $this->ajaxReturn(['status' => 301 , 'msg'=>"系统错误！",'data'=>'']);
        }
    }

    public function fifty_order($status=0){
        $user_id = $this->get_user_id();

        $goods_id = 50;

        $list = Db::table('fifty_zone_order')->where('user_id',$user_id)->where('user_confirm',0)->field('fz_order_id,order_id,shop_user_id,goods_id')->select();

        if($list){
            if($status){
                return 1;
            }

            $info = Db::table('config')->where('module',5)->column('value','name');
            if(isset($info['ailicode'])) $info['ailicode'] = Config('c_pub.apiimg') . $info['ailicode'];
            if(isset($info['wechatcode'])) $info['wechatcode'] = Config('c_pub.apiimg') . $info['wechatcode'];
            if(isset($info['mayuncode'])) $info['mayuncode'] = Config('c_pub.apiimg') . $info['mayuncode'];
            if($info['default_type']==1) $info['default_pay_code'] = $info['ailicode'];
            if($info['default_type']==2) $info['default_pay_code'] = $info['wechatcode'];
            if($info['default_type']==3) $info['default_pay_code'] = $info['mayuncode'];

            foreach($list as $key=>&$value){
                if(!$value['shop_user_id']){
                    $value['my_pic'] = $info['mayuncode'];
                    $value['wx_pic'] = $info['wechatcode'];
                    $value['zfb_pic'] = $info['ailicode'];
                    $value['mobile'] = $info['shop_mobile'];
                }else{
                    $temp = Db::table('member_payment')->where('user_id',$user_id)->field('my_pic,wx_pic,zfb_pic')->find();
                    $value['my_pic'] = $temp['mayuncode'];
                    $value['wx_pic'] = $temp['wechatcode'];
                    $value['zfb_pic'] = $temp['ailicode'];
                    $value['mobile'] = Db::name('member')->where('id',$user_id)->value('mobile');
                }
                $goods = Db::table('goods')->alias('g')->join('goods_img gi','g.goods_id=gi.goods_id','LEFT')->where('gi.main',1)->where('g.goods_id',$goods_id)->field('g.goods_name,gi.picture img')->find();
                $value['goods_name'] = $goods['goods_name'];
                $value['img'] = Config('c_pub.apiimg') . $goods['img'];
            }
        }

        if($status){
            return 0;
        }

        $this->ajaxReturn(['status' => 200 , 'msg'=>"成功！",'data'=>$list]);
    }

    public function upload_proof(){
        $user_id = $this->get_user_id();

        $fz_order_id = input('fz_order_id');
        $proof = input('proof');

        if(!$fz_order_id || !$proof){
            $this->ajaxReturn(['status' => 301 , 'msg'=>'参数错误！','data'=>'']);
        }
        $data['fz_order_id'] = $fz_order_id;

        $fifty_zone_order = Db::table('fifty_zone_order')->where('fz_order_id',$fz_order_id)->where('user_id',$user_id)->find();
        if(!$fifty_zone_order) $this->ajaxReturn(['status' => 301 , 'msg'=>'订单不存在！','data'=>'']);

        $saveName = request()->time().rand(0,99999) . '.png';

        $imga=base64_decode($proof);
        //生成文件夹
        $names = "fifty_zone" ;
        $name = "fifty_zone/" .date('Ymd',time()) ;
        if (!file_exists(ROOT_PATH .Config('c_pub.img').$names)){ 
            mkdir(ROOT_PATH .Config('c_pub.img').$names,0777,true);
        }
        //保存图片到本地
        file_put_contents(ROOT_PATH .Config('c_pub.img').$name.$saveName,$imga);

        $data['proof'] = $name.$saveName;
        $data['user_confirm'] = 1;

        $res = Db::table('fifty_zone_order')->update($data);
        if($res){
            $this->ajaxReturn(['status' => 200 , 'msg'=>'成功！','data'=>'']);
        }else{
            $this->ajaxReturn(['status' => 301 , 'msg'=>'失败！','data'=>'']);
        }
    }

    public function stay_upload_proof(){
        $user_id = $this->get_user_id();

        $goods_id = 50;

        $where['fzo.user_id'] = $user_id;
        $where['g.goods_id'] = $goods_id;
        $where['gi.main'] = 1;


        $list = Db::table('fifty_zone_order')->alias('fzo')
                ->join('goods g','g.goods_id=fzo.goods_id','LEFT')
                ->join('goods_img gi','gi.goods_id=g.goods_id','LEFT')
                ->where($where)
                ->field('fzo.fz_order_id,g.goods_name,gi.picture img')
                ->order('fzo.fz_order_id DESC')
                ->paginate(10);
        
        if($list){
            $list = $list->all();

            foreach($list as $key=>&$value){
                $value['img'] = Config('c_pub.apiimg') . $value['img'];
            }
        }

        $this->ajaxReturn(['status' => 200 , 'msg'=>'成功！','data'=>$list]);
    }
}
