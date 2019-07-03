define({ "api": [
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
  }
] });
