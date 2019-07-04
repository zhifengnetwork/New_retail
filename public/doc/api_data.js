define({ "api": [
  {
    "type": "GET",
    "url": "/banner/announce",
    "title": "获取公告",
    "group": "__",
    "version": "1.0.0",
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\"status\":200,\"msg\":\"success\",\"data\":[{\"id\":4,\"title\":\"第二个公告\",\"link\":\"www.er.com\",\"desc\":\"第二个公告\"},{\"id\":2,\"title\":\"ddd222dd\",\"link\":\"www.baidu.com\",\"desc\":\"dddd222dddddddd\"}]}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"暂无数据\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Banner.php",
    "groupTitle": "__",
    "name": "GetBannerAnnounce"
  },
  {
    "type": "GET",
    "url": "/banner/banner",
    "title": "获取banner",
    "group": "__",
    "version": "1.0.0",
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\"status\":200,\"msg\":\"success\",\"data\":[{\"picture\":\"\\\\uploads\\\\fixed_picture\\\\20190702\\\\90897f0182450d71dd9839045ee70f61.png\",\"title\":\"轮播图test\",\"url\":\"www.sogou.com\"},{\"picture\":\"\\\\uploads\\\\fixed_picture\\\\20190701\\\\de5e3e1a45e1796b5dd26acda83ff4df.png\",\"title\":\"google\",\"url\":\"www.google.com\"},{\"picture\":\"\\\\uploads\\\\fixed_picture\\\\20190529\\\\bce5780d314bb3bfd3921ffefc77fcdd.jpeg\",\"title\":\"个人中心个人资料和设置\",\"url\":\"www.cctvhong.com\"}]}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"暂无数据\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Banner.php",
    "groupTitle": "__",
    "name": "GetBannerBanner"
  },
  {
    "type": "GET",
    "url": "/goods/categoryList",
    "title": "分类",
    "group": "goods",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "无",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,\n  \"msg\": \"获取成功\",\n  \"data\": [\n      {\n      \"cat_id\": 13,\n      \"cat_name\": \"化妆品\",\n      \"pid\": 0,\n      \"level\": 1,\n      \"img\": \"category/20190516155797968450728.png\",\n      \"is_show\": 1,\n      \"desc\": \"\",\n      \"sort\": 1,\n      \"goods\": [\n          {\n          \"goods_id\": 39,\n          \"goods_name\": \"本草\",\n          \"img\": \"http://zfwl.zhifengwangluo.c3w.cc/upload/images/goods/20190514155782540787289.png\",\n          \"price\": \"200.00\",\n          \"original_price\": \"250.00\",\n          \"attr_name\": [\n              \"精选\",\n              \"限时卖\",\n              \"热卖\"\n          ],\n          \"comment\": 0\n          },\n          {\n          \"goods_id\": 18,\n          \"goods_name\": \"美的（Midea） 三门冰箱 风冷无霜家\",\n          \"img\": \"http://zfwl.zhifengwangluo.c3w.cc/upload/images/goods/20190514155782540787289.png\",\n          \"price\": \"2188.00\",\n          \"original_price\": \"2588.00\",\n          \"attr_name\": [\n              \"热卖\",\n              \"新上\"\n          ],\n          \"comment\": 18\n          }\n      ]\n      }\n  ]\n  }\n//错误返回结果\n无",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Goods.php",
    "groupTitle": "goods",
    "name": "GetGoodsCategorylist"
  },
  {
    "type": "GET",
    "url": "/goods/hot_goods",
    "title": "热门商品",
    "group": "goods",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "无",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,\n  \"msg\": \"获取成功\",\n  \"data\": [\n      {\n      \"goods_id\": 39,\n      \"goods_name\": \"本草\",\n      \"img\": \"http://zfwl.zhifengwangluo.c3w.cc/upload/images/goods/20190514155782540787289.png\",\n      \"price\": \"200.00\",\n      \"original_price\": \"250.00\"\n      },\n      {\n      \"goods_id\": 18,\n      \"goods_name\": \"美的（Midea） 三门冰箱 风冷无霜家\",\n      \"img\": \"http://zfwl.zhifengwangluo.c3w.cc/upload/images/goods/20190514155782540787289.png\",\n      \"price\": \"2188.00\",\n      \"original_price\": \"2588.00\"\n      }\n  ]\n  }\n//错误返回结果\n无",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Goods.php",
    "groupTitle": "goods",
    "name": "GetGoodsHot_goods"
  },
  {
    "type": "GET",
    "url": "/goods/recommend_goods",
    "title": "推荐商品",
    "group": "goods",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"page\":\"1\", 请求页数\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,\n  \"msg\": \"获取成功\",\n  \"data\": [\n      {\n      \"goods_id\": 39,\n      \"goods_name\": \"本草\",\n      \"img\": \"http://zfwl.zhifengwangluo.c3w.cc/upload/images/goods/20190514155782540787289.png\",\n      \"price\": \"200.00\",\n      \"original_price\": \"250.00\"\n      },\n      {\n      \"goods_id\": 18,\n      \"goods_name\": \"美的（Midea） 三门冰箱 风冷无霜家\",\n      \"img\": \"http://zfwl.zhifengwangluo.c3w.cc/upload/images/goods/20190514155782540787289.png\",\n      \"price\": \"2188.00\",\n      \"original_price\": \"2588.00\"\n      }\n  ]\n  }\n//错误返回结果\n无",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Goods.php",
    "groupTitle": "goods",
    "name": "GetGoodsRecommend_goods"
  },
  {
    "type": "POST",
    "url": "/user/distribut_list",
    "title": "佣金明细",
    "group": "user",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "token",
            "description": "<p>token*（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "page",
            "description": "<p>页数*（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"page\":\"1\"  页数 默认1,\n     \"token\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"success\",\n\"data\":[\n{\n      \"order_sn\":'RC20190116110509664542', 订单号\n      \"money\":\"8.00\", 金额\n      \"desc\":\"经理1级别利润(家用1台)\" 描述\n},\n{\n      \"order_sn\":'RC20190116110509282892',\n      \"money\":\"8.00\",\n      \"desc\":\"总监2级别利润(家用1台)\"\n},\n\n]\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"验证码错误！\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/User.php",
    "groupTitle": "user",
    "name": "PostUserDistribut_list"
  },
  {
    "type": "POST",
    "url": "/user/login",
    "title": "用户登录",
    "group": "user",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "phone",
            "description": "<p>手机号码*（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "user_password",
            "description": "<p>用户密码（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"phone\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"user_password\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"success\",\n\"data\": {\n\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJEQyIsImlhdCI6MTU2MjEyNDc0NCwiZXhwIjoxNTYyMTYwNzQ0LCJ1c2VyX2lkIjoiODAifQ.y_TRtHQ347Hl3URRJ4ECVgPbyGbniwyGyHjSjJY7fXY\",  token值，下次调用接口，需传回给后端\n\"mobile\": \"18520783339\",     手机号码\n\"id\": \"80\"       用户ID\n}\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"手机号码格式有误！\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/User.php",
    "groupTitle": "user",
    "name": "PostUserLogin"
  },
  {
    "type": "POST",
    "url": "/user/register",
    "title": "用户注册",
    "group": "user",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "phone",
            "description": "<p>手机号码*（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "verify_code",
            "description": "<p>验证码（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "user_password",
            "description": "<p>用户密码（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "confirm_password",
            "description": "<p>用户确认密码（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"phone\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"verify_code\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"user_password\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"confirm_password\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"success\",\n\"data\": {\n\"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJEQyIsImlhdCI6MTU2MjEyNDc0NCwiZXhwIjoxNTYyMTYwNzQ0LCJ1c2VyX2lkIjoiODAifQ.y_TRtHQ347Hl3URRJ4ECVgPbyGbniwyGyHjSjJY7fXY\",   token值，下次调用接口，需传回给后端\n\"mobile\": \"18520783339\",     手机号码\n\"id\": \"80\"       用户ID\n}\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"验证码错误！\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/User.php",
    "groupTitle": "user",
    "name": "PostUserRegister"
  },
  {
    "type": "POST",
    "url": "/user/sendVerifyCode",
    "title": "发送验证码",
    "group": "user",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "phone",
            "description": "<p>手机号码*（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "temp",
            "description": "<p>发送模板类型注册（sms_reg）（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "auth",
            "description": "<p>校验规则（md5(phone+temp)）（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>默认1（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"phone\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"user_password\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"success\",\n\"data\": \"发送成功！\"\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"手机号码格式有误！\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/User.php",
    "groupTitle": "user",
    "name": "PostUserSendverifycode"
  },
  {
    "type": "POST",
    "url": "/user/sharePoster",
    "title": "我的分享",
    "group": "user",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "token",
            "description": "<p>token*（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"token\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"success\",\n\"data\": {\n\"my_poster_src\": \"http:\\/\\/127.0.0.1:20019\\/shareposter\\/123-share.png\",  图片路径\n}\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"验证码错误！\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/User.php",
    "groupTitle": "user",
    "name": "PostUserShareposter"
  },
  {
    "type": "POST",
    "url": "/user/team",
    "title": "我的团队",
    "group": "user",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "token",
            "description": "<p>token*（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"token\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"success\",\n\"data\": {\n\"team_count\": \"12\",  团队人数\n\"distribut_money\": 12.20 佣金总收益\n\"estimate_money\": \"20.00\",  预计收益\n}\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"验证码错误！\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/User.php",
    "groupTitle": "user",
    "name": "PostUserTeam"
  },
  {
    "type": "POST",
    "url": "/user/team_list",
    "title": "团队列表",
    "group": "user",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "token",
            "description": "<p>token*（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"token\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"success\",\n\"data\":[\n{\n      \"id\":1, 用户ID\n      \"realname\":\"凉\", 用户名称\n       \"mobile\":\"13413695347\" 手机号\n},\n{\n      \"id\":2,\n      \"realname\":\"啦啦啦\",\n      \"mobile\":\"13413695348\"\n},\n\n]\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"验证码错误！\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/User.php",
    "groupTitle": "user",
    "name": "PostUserTeam_list"
  }
] });
