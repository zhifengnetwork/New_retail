define({ "api": [
  {
    "type": "POST",
    "url": "/cart/addCart",
    "title": "加入|修改购物车",
    "group": "cart",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"sku_id\":\"14\",规格ID\n    \"cart_number\":\"1\",购买数量\n    \"edit\":\"1\",修改购物车数量，参数传1，cart_number参数传实际要修改成的数量\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n    \"status\": 200,\n    \"msg\": \"成功\",\n    \"data\": 3,购物车ID\n}\n//错误返回结果\nstatus:301",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Cart.php",
    "groupTitle": "cart",
    "name": "PostCartAddcart"
  },
  {
    "type": "POST",
    "url": "/cart/cart_sum",
    "title": "购物车总数",
    "group": "cart",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n    \"status\": 200,\n    \"msg\": \"成功\",\n    \"data\": 3\n}\n//错误返回结果\n{\n  \"status\": 301,\n  \"msg\": \"token不存在！\",\n  \"data\": [\n      \n  ]\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Cart.php",
    "groupTitle": "cart",
    "name": "PostCartCart_sum"
  },
  {
    "type": "POST",
    "url": "/cart/cartlist",
    "title": "购物车列表",
    "group": "cart",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n    \"status\": 200,\n    \"msg\": \"成功\",\n    \"data\": [\n    {\n        \"cart_id\": 1737,购物车ID\n        \"selected\": 1,是否选中状态 0：否，1：是\n        \"user_id\": 76,\n        \"groupon_id\": 0,\n        \"goods_id\": 18,商品ID\n        \"goods_sn\": \"\",\n        \"goods_name\": \"美的（Midea） 三门冰箱 风冷无霜家\",商品名称\n        \"market_price\": \"2588.00\",市场价\n        \"goods_price\": \"2388.00\",现价\n        \"member_goods_price\": \"2388.00\",\n        \"subtotal_price\": \"2388.00\",该商品总价\n        \"sku_id\": 2,规格ID\n        \"goods_num\": 1,购买数量\n        \"spec_key_name\": \"规格:升级版,颜色:星空灰,尺寸:大\",购买规格\n        \"img\": \"http://api.retail.com/upload/images/goods/20190514155782540787289.png\",商品图片\n    },\n    {\n        \"cart_id\": 1735,\n        \"selected\": 1,\n        \"user_id\": 76,\n        \"groupon_id\": 0,\n        \"goods_id\": 39,\n        \"goods_sn\": \"\",\n        \"goods_name\": \"本草\",\n        \"market_price\": \"250.00\",\n        \"goods_price\": \"199.90\",\n        \"member_goods_price\": \"199.90\",\n        \"subtotal_price\": \"199.90\",\n        \"sku_id\": 22,\n        \"goods_num\": 1,\n        \"spec_key_name\": \"规格:颜色\",\n        \"img\": \"http://api.retail.com/upload/images/goods/20190514155782540787289.png\"\n    },\n    {\n        \"cart_id\": 1653,\n        \"selected\": 1,\n        \"user_id\": 76,\n        \"groupon_id\": 0,\n        \"goods_id\": 18,\n        \"goods_sn\": \"\",\n        \"goods_name\": \"美的（Midea） 三门冰箱 风冷无霜家\",\n        \"market_price\": \"2588.00\",\n        \"goods_price\": \"2388.00\",\n        \"member_goods_price\": \"2388.00\",\n        \"subtotal_price\": \"2388.00\",\n        \"sku_id\": 2,\n        \"goods_num\": 1,\n        \"spec_key_name\": \"规格:升级版,颜色:星空灰,尺寸:大\",\n        \"img\": \"http://api.retail.com/upload/images/goods/20190514155782540787289.png\"\n    }\n    ]\n}\n//错误返回结果\n{\n  \"status\": 301,\n  \"msg\": \"token不存在！\",\n  \"data\": [\n      \n  ]\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Cart.php",
    "groupTitle": "cart",
    "name": "PostCartCartlist"
  },
  {
    "type": "POST",
    "url": "/cart/delCart",
    "title": "删除购物车",
    "group": "cart",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"cart_id\":\"14\",购物车ID，多个逗号分开\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n    \"status\": 200,\n    \"msg\": \"成功\",\n    \"data\": \"\"\n}\n//错误返回结果\nstatus:301",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Cart.php",
    "groupTitle": "cart",
    "name": "PostCartDelcart"
  },
  {
    "type": "POST",
    "url": "/cart/selected",
    "title": "选中状态",
    "group": "cart",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"cart_id\":\"14\",购物车ID\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n    \"status\": 200,\n    \"msg\": \"成功\",\n    \"data\": \"\"\n}\n//错误返回结果\nstatus:301",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Cart.php",
    "groupTitle": "cart",
    "name": "PostCartSelected"
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
    "type": "GET",
    "url": "/index/index",
    "title": "首页",
    "group": "index",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n      \"status\": 200,\n      \"msg\": \"获取成功\",\n      \"data\": {\n          \"banners\": [\n          {\n              \"picture\": \"http://api.retail.com\\\\uploads\\\\fixed_picture\\\\20190529\\\\bce5780d314bb3bfd3921ffefc77fcdd.jpeg\",\n              \"title\": \"个人中心个人资料和设置\",\n              \"url\": \"www.cctvhong.com\"\n          },\n          {\n              \"picture\": \"http://api.retail.com\\\\uploads\\\\fixed_picture\\\\20190529\\\\94cbe33d1e15a5ebdd92cd0e3a4f4f19.jpeg\",\n              \"title\": \"13.2 我的钱包-提现记录\",\n              \"url\": \"www.ceshi.com\"\n          },\n          {\n              \"picture\": \"http://api.retail.com\\\\uploads\\\\fixed_picture\\\\20190529\\\\414eac4f30c011288ae42e822cb637cc.jpeg\",\n              \"title\": \"钱包转换\",\n              \"url\": \"www.ceshi.com\"\n          }\n          ],banner轮播图\n          \"announce\": [\n          \n          ],公告\n          \"hot_goods\": [\n          {\n              \"goods_id\": 39,\n              \"goods_name\": \"本草\",\n              \"img\": \"http://zfwl.zhifengwangluo.c3w.cc/upload/images/goods/20190514155782540787289.png\",\n              \"price\": \"200.00\",\n              \"original_price\": \"250.00\"\n          },\n          {\n              \"goods_id\": 18,\n              \"goods_name\": \"美的（Midea） 三门冰箱 风冷无霜家\",\n              \"img\": \"http://zfwl.zhifengwangluo.c3w.cc/upload/images/goods/20190514155782540787289.png\",\n              \"price\": \"2188.00\",\n              \"original_price\": \"2588.00\"\n          }\n          ],热门商品\n          \"recommend_goods\": {\n          \"total\": 2,\n          \"per_page\": 4,\n          \"current_page\": 1,\n          \"last_page\": 1,\n          \"data\": [\n              {\n              \"goods_id\": 39,\n              \"goods_name\": \"本草\",\n              \"img\": \"http://zfwl.zhifengwangluo.c3w.cc/upload/images/goods/20190514155782540787289.png\",\n              \"price\": \"200.00\",\n              \"original_price\": \"250.00\"\n              },\n              {\n              \"goods_id\": 36,\n              \"goods_name\": \"美的（Midea） 三门冰箱 \",\n              \"img\": \"http://zfwl.zhifengwangluo.c3w.cc/upload/images/goods/20190514155782540787289.png\",\n              \"price\": \"50.00\",\n              \"original_price\": \"40.00\"\n              }\n          ]\n          }推荐商品\n      }\n      }\n//错误返回结果\n无",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "index",
    "name": "GetIndexIndex"
  },
  {
    "type": "POST",
    "url": "/order/apply_refund",
    "title": "申请退款",
    "group": "order",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"order_id\":\"12\", 订单ID\n    \"refund_type\":\"\", 退款方式\n    \"refund_reason\":\"\", 退款理由\n    \"cancel_remark\":\"\", 退款备注\n    \"img\":\"\", 图片（base64）数组\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,\n  \"msg\": \"成功！\",\n  \"data\": {\n  }\n  }\n//错误返回结果\n{\n  \"status\": 301,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Order.php",
    "groupTitle": "order",
    "name": "PostOrderApply_refund"
  },
  {
    "type": "POST",
    "url": "/order/cancel_refund",
    "title": "取消申请退款",
    "group": "order",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"order_id\":\"12\", 订单ID\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,shu\n  \"msg\": \"取消申请退款成功！\",\n  \"data\": {\n  }\n  }\n//错误返回结果\n{\n  \"status\": 301,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Order.php",
    "groupTitle": "order",
    "name": "PostOrderCancel_refund"
  },
  {
    "type": "POST",
    "url": "/order/edit_status",
    "title": "修改订单状态(取消订单|确认收货|删除订单)",
    "group": "order",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"order_id\":\"12\",订单ID\n    \"status\":\"1\",订单状态\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,\n  \"msg\": \"获取成功\",\n  \"data\": {\n  }\n}\n//错误返回结果\n{\n  \"status\": 301,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Order.php",
    "groupTitle": "order",
    "name": "PostOrderEdit_status"
  },
  {
    "type": "POST",
    "url": "/order/get_refund",
    "title": "获取退款信息",
    "group": "order",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"order_id\":\"12\", 订单ID\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,\n  \"msg\": \"成功！\",\n  \"data\": {\n  \"consignee\": \"董惠纺\",联系人\n  \"mobile\": \"15847059545\",联系电话\n  \"refund_reason\": [\n      \"7天无理由退款\",\n      \"退运费\",\n      \"商品描述不符\",\n      \"质量问题\",\n      \"少件漏发\",\n      \"包装/商品破损/污渍\",\n      \"发票问题\",\n      \"卖家发错货\"\n  ],退款理由\n  \"refund_type\": [\n      \"支付原路退回\",\n      \"退到用户余额\"\n  ]退款方式\n  }\n  }\n//错误返回结果\n{\n  \"status\": 301,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Order.php",
    "groupTitle": "order",
    "name": "PostOrderGet_refund"
  },
  {
    "type": "POST",
    "url": "/order/order_comment",
    "title": "订单商品评论",
    "group": "order",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"comments\":\"\",json包array,示例comments\t是\tjson\tjson对象包数组 comments[order_id]\t是\tjson\t订单ID comments[goods_id]\t是\tjson\t商品ID comments[sku_id]\t是\tjson\t规格ID comments[star_rating]\t是\tjson\t星评1-5 comments[content]\t否\tjson\t评论内容 comments[img]\t否\tjson[array]\t评论图片（base64）\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,\n  \"msg\": \"成功！\",\n  \"data\": {\n  }\n}\n//错误返回结果\n{\n  \"status\": 301,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Order.php",
    "groupTitle": "order",
    "name": "PostOrderOrder_comment"
  },
  {
    "type": "POST",
    "url": "/order/order_comment_list",
    "title": "获取订单评论",
    "group": "order",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"order_id\":\"12\", 订单ID\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,\n  \"msg\": \"成功！\",\n  \"data\": [\n  {\n      \"goods_id\": 18,\n      \"sku_id\": 2,\n      \"goods_name\": \"美的（Midea） 三门冰箱 风冷无霜家\",\n      \"goods_num\": 1,\n      \"spec_key_name\": \"规格:升级版,颜色:星空灰,尺寸:大\",\n      \"img\": \"goods/20190704156222261239875.png\"\n  }\n  ]\n}\n//错误返回结果\n{\n  \"status\": 301,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Order.php",
    "groupTitle": "order",
    "name": "PostOrderOrder_comment_list"
  },
  {
    "type": "POST",
    "url": "/order/order_detail",
    "title": "订单详情",
    "group": "order",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"order_id\":\"12\",订单ID\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,\n  \"msg\": \"获取成功\",\n  \"data\": {\n  \"order_id\": 1631,订单ID\n  \"order_sn\": \"20190704150438408830\",订单编号\n  \"order_status\": 1,\n  \"pay_status\": 0,\n  \"shipping_status\": 0,\n  \"pay_type\": {\n      \"pay_type\": 2,\n      \"pay_name\": \"微信支付\"\n  },支付方式\n  \"consignee\": \"董惠纺\",收货人\n  \"mobile\": \"15847059545\",收货手机号\n  \"address\": \"内蒙古自治区赤峰市红山区内蒙古赤峰市红山区三道街植物园路口二毛对夹\",收货地址\n  \"coupon_price\": \"0.00\",优惠金额\n  \"order_amount\": \"2388.00\",订单金额（实付金额）\n  \"total_amount\": \"2388.00\",订单总金额\n  \"add_time\": 1562223878,\n  \"shipping_name\": \"\",\n  \"shipping_price\": \"0.00\",物流费用\n  \"user_note\": \"\",下单备注\n  \"pay_time\": 0,付款时间\n  \"user_money\": \"0.00\",使用余额\n  \"status\": 1,订单状态 1：待付款,2：待发货,3：待收货,4：待评价,5：已取消,6：待退款,7：已退款,8：拒绝退款\n  \"order_refund\": {\n      \"count_num\": 1\n  },\n  \"goods_res\": [\n      {\n      \"goods_id\": 18,\n      \"goods_name\": \"美的（Midea） 三门冰箱 风冷无霜家\",\n      \"goods_num\": 1,\n      \"spec_key_name\": \"规格:升级版,颜色:星空灰,尺寸:大\",\n      \"goods_price\": \"2388.00\",\n      \"original_price\": \"2588.00\",\n      \"img\": \"http://api.retail.com/upload/images/goods/20190704156222261239875.png\"\n      }\n  ]\n  }\n  }\n//错误返回结果\n{\n  \"status\": 301,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Order.php",
    "groupTitle": "order",
    "name": "PostOrderOrder_detail"
  },
  {
    "type": "POST",
    "url": "/order/order_list",
    "title": "订单列表",
    "group": "order",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"type\":\"all\",类型 all：全部订单，dfk：待付款，dfh:代发货，dsh：待收货，dpj：待评价，tk：退款，yqx：已取消 \n    \"page\":\"1\",请求页数 \n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n  \"status\": 200,\n  \"msg\": \"获取成功\",\n  \"data\": [\n      {\n      \"order_id\": 1631,订单ID\n      \"order_sn\": \"20190704150438408830\",订单编号\n      \"goods_name\": \"美的（Midea） 三门冰箱 风冷无霜家\",商品名称\n      \"img\": \"http://api.retail.com/upload/images/goods/20190704156222261239875.png\",\n      \"spec_key_name\": \"规格:升级版,颜色:星空灰,尺寸:大\",购买规格\n      \"goods_price\": \"2388.00\",商品价格\n      \"original_price\": \"2588.00\",市场价\n      \"goods_num\": 1,购买数量\n      \"order_status\": 1,\n      \"pay_status\": 0,\n      \"shipping_status\": 0,\n      \"pay_type\": 2,\n      \"comment\": 0,是否评论 0：否，1：是\n      \"status\": 1 订单状态 1：待付款,2：待发货,3：待收货,4：待评价,5：已取消,6：待退款,7：已退款,8：拒绝退款\n      }\n  ]\n  }\n//错误返回结果\n{\n  \"status\": 301,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Order.php",
    "groupTitle": "order",
    "name": "PostOrderOrder_list"
  },
  {
    "type": "POST",
    "url": "/order/submitOrder",
    "title": "提交订单",
    "group": "order",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"cart_id\":\"12,13\",购物车ID，多个逗号分开\n    \"address_id\":\"13\",地址ID\n    \"coupon_id\":\"\",优惠券ID（没有可不传）\n    \"pay_type\":\"\",支付类型\n    \"user_note\":\"\",下单备注\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n      \"status\": 200,\n      \"msg\": \"成功\",\n      \"data\": 21,订单ID\n}\n//错误返回结果\n{\n  \"status\": 301,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Order.php",
    "groupTitle": "order",
    "name": "PostOrderSubmitorder"
  },
  {
    "type": "POST",
    "url": "/order/temporary",
    "title": "购物车提交订单",
    "group": "order",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\", \n    \"cart_id\":\"12,13\"购物车ID，多个逗号分开\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n      \"status\": 200,\n      \"msg\": \"成功\",\n      \"data\": {\n          \"goods_res\": [\n          {\n              \"cart_id\": 1737,购物车ID\n              \"selected\": 1,\n              \"user_id\": 76,\n              \"groupon_id\": 0,\n              \"goods_id\": 18,商品ID\n              \"goods_sn\": \"\",\n              \"goods_name\": \"美的（Midea） 三门冰箱 风冷无霜家\",商品名称\n              \"market_price\": \"2588.00\",市场价\n              \"goods_price\": \"2388.00\",现价\n              \"member_goods_price\": \"2388.00\",\n              \"subtotal_price\": \"2388.00\",\n              \"sku_id\": 2,规格ID\n              \"goods_num\": 1,购买数量\n              \"spec_key_name\": \"规格:升级版,颜色:星空灰,尺寸:大\",购买规格\n              \"img\": \"http://api.retail.com/upload/images/goods/20190514155782540787289.png\"商品图片\n          }\n          ],\n          \"addr_res\": [\n          \n          ],地址\n          \"pay_type\": [\n          {\n              \"pay_type\": 2,\n              \"pay_name\": \"微信支付\"\n          },\n          {\n              \"pay_type\": 1,\n              \"pay_name\": \"余额支付\"\n          },\n          {\n              \"pay_type\": 4,\n              \"pay_name\": \"货到付款\"\n          },\n          {\n              \"pay_type\": 3,\n              \"pay_name\": \"支付宝支付\"\n          }\n          ],支付类型\n          \"shipping_price\": \"0.00\",物流费用\n          \"coupon\": [\n          \n          ],可用优惠券\n      }\n      }\n//错误返回结果\n{\n  \"status\": 301,\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Order.php",
    "groupTitle": "order",
    "name": "PostOrderTemporary"
  },
  {
    "type": "POST",
    "url": "/search/get_search",
    "title": "搜索页面",
    "group": "search",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "token",
            "description": "<p>token值*（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"token\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"成功！\",\n\"data\": {\n\"hot\": [   热搜\n{\n\"keywords\": \"补水\",    关键字\n\"cat_id\": 15           分类ID\n},\n{\n\"keywords\": \"美柔柔弱\",\n\"cat_id\": 0\n}\n],\n\"history\": [    最近搜索\n{\n\"keywords\": \"补水\",   关键字\n\"cat_id\": 15          分类ID\n},\n{\n\"keywords\": \"美柔柔弱\",\n\"cat_id\": 0\n}\n]\n}\n}\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"用户不存在\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Search.php",
    "groupTitle": "search",
    "name": "PostSearchGet_search"
  },
  {
    "type": "POST",
    "url": "/search/search",
    "title": "点击搜索",
    "group": "search",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "token",
            "description": "<p>token值*（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"token\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"获取成功\",\n\"data\": {\n\"cate_list\": [],\n\"goods_list\": [  //商品列表\n{\n\"goods_id\": 22,      商品ID\n\"img\": \"goods/20190704156222264985070.png\",      图片地址\n\"goods_name\": \"美的（Midea）333\",    名称\n\"desc\": \"\",\n\"price\": \"3455.00\",\n\"original_price\": \"4566.00\",\n\"goods_attr\": \"商品属性\",\n\"comment\": 0,\n\"attr_name\": []\n},\n{\n\"goods_id\": 19,\n\"img\": \"goods/20190704156222271022686.png\",\n\"goods_name\": \"美的（Midea） 三门冰箱1\",\n\"desc\": \"燃气热水器16L 水气双调变频恒温 智能变升随温感 六重安防 ECO节能JSQ30-TC5（天然气）                        \",\n\"price\": \"2000.00\",\n\"original_price\": \"2500.00\",\n\"goods_attr\": \"2\",\n\"comment\": 4,\n\"attr_name\": [\n\"新上\"\n]\n}\n]\n}\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"用户不存在\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Search.php",
    "groupTitle": "search",
    "name": "PostSearchSearch"
  },
  {
    "type": "GET",
    "url": "/address/get_region",
    "title": "获取地区下级",
    "group": "user",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "code",
            "description": "<p>地址code（选填  如果没有参数会返回所有的省份）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"code\":\"xxxxxxxx\",\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\"status\":200,\n\"msg\":\"success\",\n\"data\":[{\"area_id\":3,\"code\":\"1101\",\"parent_id\":\"11\",\"area_name\":\"北京市\",\"area_type\":2}]\n}\n* //错误返回结果\n{\n\"status\": 301,\n\"msg\": \"没有数据\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Address.php",
    "groupTitle": "user",
    "name": "GetAddressGet_region"
  },
  {
    "type": "POST",
    "url": "/address/addAddress",
    "title": "地址添加和编辑",
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
            "field": "address_id",
            "description": "<p>address_id（选填,没有为添加模式）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "consignee",
            "description": "<p>客户名称（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "district",
            "description": "<p>区id（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "address",
            "description": "<p>地址（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "mobile",
            "description": "<p>地址（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "is_default",
            "description": "<p>默认地址（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"token\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"address_id\":\"xxxxxxxx\",\n     \"consignee\"：\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"district\"：\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"address\"：\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"mobile\"：\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"is_default\"：\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\"status\":200,\n\"msg\":\"添加成功\",\n\"data\":[]\n}\n* //错误返回结果\n{\n\"status\": 301,\n\"msg\": \"操作失败\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Address.php",
    "groupTitle": "user",
    "name": "PostAddressAddaddress"
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
    "url": "/address/delAddress",
    "title": "地址删除",
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
            "field": "id",
            "description": "<p>地址id（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"token\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"id\":\"xxxxxxxx\",\n \n     \n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\"status\":200,\n\"msg\":\"删除成功\",\n\"data\":[]\n}\n* //错误返回结果\n{\n\"status\": 301,\n\"msg\": \"操作失败\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Address.php",
    "groupTitle": "user",
    "name": "PostAddressDeladdress"
  },
  {
    "type": "POST",
    "url": "/pay/set_payment",
    "title": "设置收款",
    "group": "user",
    "version": "1.0.0",
    "parameter": {
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n    \"token\":\"\",           token\n     \"type\":''            当前操作的类型 1:码云  2:微信   3:支付宝\n     \"name\":\"\"              昵称\n     \"image\":\"\"           收款码图片\n    \n         \n    操作对应的类型,填写对应的昵称和图片路径\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\"status\":200,\"msg\":\"success\",\"data\":\"操作成功\"}\n//错误返回结果\n{\"status\":301,\"msg\":\"fail\",\"data\":\"操作失败\"}\n无",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Pay.php",
    "groupTitle": "user",
    "name": "PostPaySet_payment"
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
    "url": "/user/edit_name",
    "title": "修改用户名",
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
            "field": "realname",
            "description": "<p>姓名*（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"realname\":\"xxxxxxxxxxxxxxxxxxxxxx\",\n     \"token\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"success\",\n\"data\":\"成功\"\n\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"操作失败\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/User.php",
    "groupTitle": "user",
    "name": "PostUserEdit_name"
  },
  {
    "type": "POST",
    "url": "/user/estimate_list",
    "title": "预计收益明细",
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
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"success\",\n\"data\":[\n{\n      \"user_id\"  : 123132, 用户ID\n      \"realname\":'张三', 用户名称\n      \"order_sn\":'RC20190116110509664542', 订单号\n      \"money\":\"8.00\", 金额\n},\n{\n     \"user_id\"  : 123132, 用户ID\n      \"realname\":'张三', \n      \"order_sn\":'RC20190116110509282892',\n      \"money\":\"8.00\",\n},\n\n]\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"验证码错误！\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/User.php",
    "groupTitle": "user",
    "name": "PostUserEstimate_list"
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
    "url": "/user/resetPassword",
    "title": "修改密码",
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
            "field": "type",
            "description": "<p>1 登录密码；2 支付密码*（必填）</p>"
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
          "content": "{\n     \"phone\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"type\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"verify_code\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"user_password\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"confirm_password\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"success\",\n\"data\": \"修改成功\"\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"验证码错误！\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/User.php",
    "groupTitle": "user",
    "name": "PostUserResetpassword"
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
            "description": "<p>发送模板类型：注册 sms_reg；忘记密码 sms_forget（必填）</p>"
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
            "description": "<p>1登录密码 2支付密码（必填）</p>"
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
    "title": "我的推广码",
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
  },
  {
    "type": "POST",
    "url": "/user/user_info",
    "title": "我的信息",
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
          "content": "//正确返回结果\n{\n\"status\": 200,\n\"msg\": \"success\",\n\"data\": {\n\"id\": \"12\",  用户id\n\"mobile\": 12.20 手机号\n\"realname\": \"20.00\", 用户名称\n\"remainder_money\": \"20.00\",  余额\n\"distribut_money\": \"20.00\",  佣金累计收益\n\"estimate_money\": \"20.00\",   预计收益\n\"createtime\": \"\",  注册时间\n\"avatar\": \"\",  头像\n\"collection\": \"20\",  收藏\n\"not_pay\" : 0 ,待付款\n\"not_delivery\" : 0 ,待发货\n\"not_receiving\" : 0 ,待收货\n\"not_evaluate\" : 0 ,待评价\n\"refund\" : 0 ,退款\n\n}\n}\n//错误返回结果\n{\n\"status\": 301,\n\"msg\": \"验证码错误！\",\n\"data\": false\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/User.php",
    "groupTitle": "user",
    "name": "PostUserUser_info"
  }
] });
