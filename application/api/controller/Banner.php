<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 2019/5/29
 * Time: 18:43
 */

namespace app\api\controller;

use app\common\controller\ApiAbstract;
use think\Db;

class Banner extends ApiAbstract
{

       /**
     * @api {GET} /banner/banner 获取banner
     * @apiGroup banner
     * @apiVersion 1.0.0
     *
     * @apiSuccessExample {json} 返回数据：
     * //正确返回结果
     *{"status":200,"msg":"success","data":[{"picture":"\\uploads\\fixed_picture\\20190702\\90897f0182450d71dd9839045ee70f61.png","title":"轮播图test","url":"www.sogou.com"},{"picture":"\\uploads\\fixed_picture\\20190701\\de5e3e1a45e1796b5dd26acda83ff4df.png","title":"google","url":"www.google.com"},{"picture":"\\uploads\\fixed_picture\\20190529\\bce5780d314bb3bfd3921ffefc77fcdd.jpeg","title":"个人中心个人资料和设置","url":"www.cctvhong.com"}]}
     * //错误返回结果
     * {
     * "status": 301,
     * "msg": "暂无数据",
     * "data": false
     * }
     */
    public function banner () {
        $banners=Db::name('advertisement')->field('picture,title,url')->where(['type'=>0,'state'=>1])->order('sort','desc')->limit(3)->select();
        if($banners){
            return $this->successResult($banners);
        }else{
            return $this->failResult('暂无数据', 301);
        }
    }

    public function announce()
    {
        $announce=Db::name('announce')->field('id,title,urllink as link,desc')->where(['status'=>1])->order('create_time','desc')->limit(3)->select();
        if($announce){
            return $this->successResult($announce);
        }else{
            return $this->failResult("暂无数据",401);
        }    
    }



    public function getAllData ($only_logo,$type=0){
        return Db::table('page_advertisement')->alias('a')->join('advertisement b','a.id = b.page_id','left')
            ->where(['a.only_logo'=>$only_logo,'a.status'=>1,'b.state'=>1,'b.type'=>$type])->field('b.*')->select();
    }


}