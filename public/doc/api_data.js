define({ "api": [
  {
    "type": "POST",
    "url": "/api/index/sendRegisterCode",
    "title": "获取验证码",
    "group": "index",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "user_name",
            "description": "<p>用户名(邮箱或手机号码)*（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "login_type",
            "description": "<p>1 手机 2邮箱（必填）</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "area_code",
            "description": "<p>当login_type为1时，区号（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"user_name\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n     \"area_code\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "{\n\"status\": 200,\n\"msg\": \"验证码发送成功\",\n\"data\": ''\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "index",
    "name": "PostApiIndexSendregistercode"
  },
  {
    "type": "POST",
    "url": "/api/index/userInfo",
    "title": "用户信息",
    "group": "index",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "user_id",
            "description": "<p>用户ID*（必填）</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "请求数据:",
          "content": "{\n     \"user_id\":\"xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx\",\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "返回数据：",
          "content": "{\n\"status\": 200,\n\"msg\": \"验证码发送成功\",\n\"data\": ''\n}",
          "type": "json"
        }
      ]
    },
    "filename": "application/api/controller/Index.php",
    "groupTitle": "index",
    "name": "PostApiIndexUserinfo"
  }
] });
