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
    "url": "/goods/comment_list",
    "title": "商品评论",
    "group": "goods",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"token\",\n     \"goods_id\",商品ID\n     \"page\",请求页数\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n     \"status\": 1,\n     \"msg\": \"获取成功\",\n     \"data\": [\n     {\n         \"mobile\": \"\",手机号\n         \"user_id\": 0,\n         \"comment_id\": 13,\n         \"content\": null,评论内容\n         \"star_rating\": 5,星评\n         \"replies\": null,商家回复\n         \"praise\": 0,点赞\n         \"add_time\": 1558087885,\n         \"img\": [\n         \n         ],评论图片\n         \"sku_id\": 4,\n         \"spec\": \"规格:升级版,颜色:阳光米,尺寸:小\",购买规格\n         \"is_praise\": 0,是否已点赞改评论 0：否，1：是\n     }\n     ]\n}\n//错误返回结果\n无",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Goods.php",
    "groupTitle": "goods",
    "name": "PostGoodsComment_list"
  },
  {
    "type": "POST",
    "url": "/goods/goodsDetail",
    "title": "商品详情",
    "group": "goods",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\",\n    \"goods_id\",商品ID\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,\n  \"msg\": \"获取成功\",\n  \"data\": {\n  \"goods_id\": 18,商品ID\n  \"goods_name\": \"美的（Midea） 三门冰箱 风冷无霜家\",商品名称\n  \"type_id\": 0,\n  \"desc\": \"宽幅变温；铂金净味；雷达感温；风冷无霜；\",描述\n  \"content\": null,内容\n  \"goods_attr\": \"2,3\",\n  \"limited_start\": 1559318400,\n  \"limited_end\": 1559577600,\n  \"add_time\": 1557417600,\n  \"goods_spec\": \"[{\\\"key\\\":\\\"规格\\\",\\\"value\\\":\\\"默认;升级版;超级版\\\"},{\\\"key\\\":\\\"颜色\\\",\\\"value\\\":\\\"阳光米;星空灰;天空黑\\\"},{\\\"key\\\":\\\"尺寸\\\",\\\"value\\\":\\\"中;大;小;加大\\\"}]\",\n  \"price\": \"2188.00\",现价\n  \"original_price\": \"2588.00\",市场价\n  \"cost_price\": \"10.00\",\n  \"cat_id1\": 13,\n  \"cat_id2\": 14,\n  \"stock\": 1190,总库存数量\n  \"stock1\": 1193,\n  \"less_stock_type\": 2,\n  \"shipping_setting\": 1,运费方式 1：统一运费价格 ，2：运费模板\n  \"shipping_price\": \"0.00\",统一运费价格\n  \"delivery_id\": 0,\n  \"is_hdfk\": 0,是否支持货到付款 0：否，1：是\n  \"is_distribution\": 1,\n  \"most_buy_number\": 10000,\n  \"gift_points\": \"10%\",\n  \"number_sales\": 66,已售数量\n  \"single_number\": 10000,单次最多购买量\n  \"distributor_level\": 0,\n  \"is_full_return\": 0,\n  \"is_arrange_all\": 0,\n  \"shopping_all_return\": 0,\n  \"is_show\": 1,\n  \"dividend_agent_level\": 0,\n  \"is_del\": 0,\n  \"is_puls\": 0,\n  \"province_proportion\": 0,\n  \"tow_proportion\": 0,\n  \"infinite_proportion\": 0,\n  \"puls_discount\": 0,\n  \"share_discount\": 0,\n  \"attr_name\": [\n      \"新上\",\n      \"热卖\"\n  ],商品属性\n  \"spec\": {\n      \"spec_attr\": [\n      {\n          \"spec_id\": 1,\n          \"spec_name\": \"规格\",规格名称\n          \"res\": [\n          {\n              \"attr_id\": 34478,\n              \"attr_name\": \"默认\"规格值\n          },\n          {\n              \"attr_id\": 34480,\n              \"attr_name\": \"升级版\"\n          },\n          {\n              \"attr_id\": 34494,\n              \"attr_name\": \"超级版\"\n          }\n          ]\n      },\n      {\n          \"spec_id\": 2,\n          \"spec_name\": \"颜色\",\n          \"res\": [\n          {\n              \"attr_id\": 34479,\n              \"attr_name\": \"阳光米\"\n          },\n          {\n              \"attr_id\": 34481,\n              \"attr_name\": \"星空灰\"\n          },\n          {\n              \"attr_id\": 34495,\n              \"attr_name\": \"天空黑\"\n          }\n          ]\n      },\n      {\n          \"spec_id\": 4,\n          \"spec_name\": \"尺寸\",\n          \"res\": [\n          {\n              \"attr_id\": 34484,\n              \"attr_name\": \"中\"\n          },\n          {\n              \"attr_id\": 34485,\n              \"attr_name\": \"大\"\n          },\n          {\n              \"attr_id\": 34486,\n              \"attr_name\": \"小\"\n          },\n          {\n              \"attr_id\": 34496,\n              \"attr_name\": \"加大\"\n          }\n          ]\n      }\n      ],\n      \"goods_sku\": [\n      {\n          \"sku_id\": 1,规格ID\n          \"goods_id\": 18,\n          \"sku_attr\": \"{\\\"1\\\":34478,\\\"2\\\":34479,\\\"4\\\":34484}\",\n          \"price\": \"2199.00\",价格\n          \"groupon_price\": \"1999.00\",\n          \"img\": \"\",\n          \"inventory\": 0,剩余库存\n          \"frozen_stock\": 2,\n          \"sales\": 0,销量\n          \"virtual_sales\": 559,虚拟销量\n          \"sku_attr1\": \"34478,34479,34484\"\n      },\n      {\n          \"sku_id\": 2,\n          \"goods_id\": 18,\n          \"sku_attr\": \"{\\\"1\\\":34480,\\\"2\\\":34481,\\\"4\\\":34485}\",\n          \"price\": \"2388.00\",\n          \"groupon_price\": \"2288.00\",\n          \"img\": \"\",\n          \"inventory\": 493,\n          \"frozen_stock\": 0,\n          \"sales\": 0,\n          \"virtual_sales\": 472,\n          \"sku_attr1\": \"34480,34481,34485\"\n      },\n      {\n          \"sku_id\": 4,\n          \"goods_id\": 18,\n          \"sku_attr\": \"{\\\"1\\\":34480,\\\"2\\\":34479,\\\"4\\\":34486}\",\n          \"price\": \"1988.00\",\n          \"groupon_price\": \"1799.00\",\n          \"img\": \"\",\n          \"inventory\": 298,\n          \"frozen_stock\": 1,\n          \"sales\": 0,\n          \"virtual_sales\": 538,\n          \"sku_attr1\": \"34480,34479,34486\"\n      },\n      {\n          \"sku_id\": 10,\n          \"goods_id\": 18,\n          \"sku_attr\": \"{\\\"1\\\":34494,\\\"2\\\":34495,\\\"4\\\":34496}\",\n          \"price\": \"2500.00\",\n          \"groupon_price\": \"2300.00\",\n          \"img\": \"\",\n          \"inventory\": 199,\n          \"frozen_stock\": 0,\n          \"sales\": 0,\n          \"virtual_sales\": 443,\n          \"sku_attr1\": \"34494,34495,34496\"\n      },\n      {\n          \"sku_id\": 11,\n          \"goods_id\": 18,\n          \"sku_attr\": \"{\\\"1\\\":34494,\\\"2\\\":34481,\\\"4\\\":34485}\",\n          \"price\": \"2488.00\",\n          \"groupon_price\": \"2388.00\",\n          \"img\": \"\",\n          \"inventory\": 200,\n          \"frozen_stock\": 0,\n          \"sales\": 0,\n          \"virtual_sales\": 450,\n          \"sku_attr1\": \"34494,34481,34485\"\n      }\n      ]\n  },商品规格\n  \"groupon_price\": \"1799.00\",\n  \"img\": [\n      {\n      \"picture\": \"http://zfwl.zhifengwangluo.c3w.cc/upload/images/goods/20190514155782540787289.png\"\n      }\n  ],商品组图\n  \"collection\": 0,是否收藏\n  \"comment_count\": 18,评论总数\n  \"coupon\": [\n      \n  ]优惠券\n  }\n}\n//错误返回结果\n{\n    \"status\": 999,\n    \"msg\": \"用户不存在！\",\n    \"data\": false\n}\n{\n    \"status\": 301,\n    \"msg\": \"商品不存在！\",\n    \"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Goods.php",
    "groupTitle": "goods",
    "name": "PostGoodsGoodsdetail"
  },
  {
    "type": "POST",
    "url": "/address/addressList",
    "title": "地址列表",
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
          "content": "//正确返回结果\n{\"status\":200,\n\"msg\":\"success\",\n\"data\":[{\n     \"address_id\":1055,\n     \"consignee\":\"等奖\",\n     \"mobile\":\"15181112455\",\n     \"address\":\"啊实打实的\",\n     \"is_default\":1,\n     \"p_cn\":\"北京市\",\n      \"c_cn\":\"北京市\",\n     \"d_cn\":\"东城区\",\n     \"s_cn\":null\n}]}\n* //错误返回结果\n{\n\"status\": 301,\n\"msg\": \"暂无地址信息\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Address.php",
    "groupTitle": "user",
    "name": "PostAddressAddresslist"
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
