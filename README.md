<h1 align="center">Laravel-sms</h1>

laravel框架专用发短信，支持多种短信渠道，如创蓝等。


## 安装
使用composer安装
```
    $ composer require mythinking/laravel-sms
    
    $ php artisan vendor:publish --provider="Mythinking\LaravelSms\ServiceProvider"
```


## 使用

#### 1.在app.php的 providers 加入
```
    Mythinking\LaravelSms\ServiceProvider::class,
```

#### 2.在app.php的 aliases 加入
```
    'Sms' => Mythinking\LaravelSms\Facades\Sms::class,
```

#### 3.在config/sms.php中配置对应的值
- 支持国内/国际短信, 分账号配置
- 支持模板短信
- 每日限制发送短信数量
- 短信有效期

#### 4.代码中使用
````
    use Sms; 
    
    $server = Sms::server();
    $server->setConfig(config("sms.cl"));//支持多账号配置,默认cl配置
    $res = $server->send('15988888888','registerCode', ['code'=>123456], function ($res, $data) {
        Log::info($res);//接口返回结果, code=0 成功
            array:3 [▼
              "code" => 101
              "msg" => "无此用户"
              "data" => array:2 [▼
                "msgid" => ""
                "time" => "20190905160335"
              ]
            ]
        Log::info($data);//渠道方返回结果
                array:2 [▼
                  "msg" => "【xxx】您的验证码为：123456，请在三分钟内输入。为了保障您的账户安全，请勿将验证码短信转发他人"
                  "res" => "{"code":"101","msgId":"","time":"20190905160047","errorMsg":"无此用户"}"
                ]
        // db record
        ...
    });
    
    
    //清除限制缓存
    $server->clearLimit('15988888888');
````

#### 5. todo
- 更多渠道短信支持, 如阿里云等


## 支持短信渠道列表

- [创蓝](https://zz.253.com/ "创蓝")