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
     *{"status":200,"msg":"success","data":[{"id":12,"sort":4,"picture":"\\uploads\\fixed_picture\\20190702\\90897f0182450d71dd9839045ee70f61.png","state":1,"update_time":0,"create_time":1562030000,"page_id":1,"url":"www.sogou.com","type":0},{"id":11,"sort":3,"picture":"\\uploads\\fixed_picture\\20190701\\de5e3e1a45e1796b5dd26acda83ff4df.png","state":1,"update_time":0,"create_time":1561976397,"page_id":1,"url":"www.google.com","type":0},{"id":5,"sort":2,"picture":"\\uploads\\fixed_picture\\20190529\\bce5780d314bb3bfd3921ffefc77fcdd.jpeg","state":1,"update_time":0,"create_time":1559122339,"page_id":1,"url":"www.cctvhong.com","type":0}]}
     * //错误返回结果
     * {
     * "status": 401,
     * "msg": "暂无数据",
     * "data": false
     * }
     */
    public function banner () {
        $banners=Db::name('advertisement')->field('title','url')->where(['type'=>0])->order('sort','desc')->limit(3)->select();
        if($banners){
            return $this->successResult($banners);
        }else{
            return $this->failResult('暂无数据', 401);
        }
    }

    public function getAllData ($only_logo,$type=0){
        return Db::table('page_advertisement')->alias('a')->join('advertisement b','a.id = b.page_id','left')
            ->where(['a.only_logo'=>$only_logo,'a.status'=>1,'b.state'=>1,'b.type'=>$type])->field('b.*')->select();
    }
}