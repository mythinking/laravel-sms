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

#### 4.代码中使用
````
    use Sms; 
    
    $res = Sms::server()->send('15989214100','registerCode', ['code'=>123456], function ($v) {
        Log::info($v);
        // db record
        ...
    });
````


## 支持短信渠道列表

- [创蓝](https://zz.253.com/ "创蓝")