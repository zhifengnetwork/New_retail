<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

define('HTTP_HOST', $_SERVER['HTTP_HOST']);
//http://new_retail.zhifengwangluo.com/
if (preg_match("/(.*)\.zhifengwangluo\.com/i", HTTP_HOST, $matches)) {
    $partner = $matches[1];
    $terrace = [
        'new_retail'        => 'admin',
        'new_retail_api'    => 'api',
    ];
    $module = isset($terrace[$partner]) ? $terrace[$partner] : 'home';
    define('BIND_MODULE', $module);
} else {
    $terrace = [
        '127.0.0.1:10060' => 'kf',
        '127.0.0.1:10059' => 'agent',
        '127.0.0.1:10058' => 'home',
        '127.0.0.1:10057' => 'sapi',
        '127.0.0.1:20019' => 'admin',
        'api.myxls.com' => 'api',
        'www.myxls.com' => 'admin',
    ];
    if (!empty($terrace[HTTP_HOST])) {
        $module = $terrace[HTTP_HOST];
        define('BIND_MODULE', $module);
    }
}
$http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
define('SITE_URL',$http.'://'.$_SERVER['HTTP_HOST']); // 网站域名

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
