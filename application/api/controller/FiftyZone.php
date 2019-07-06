<?php
/**
 * 50元专区
 */
namespace app\api\controller;
use app\common\controller\ApiBase;
use think\Db;

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



    }
    

}
