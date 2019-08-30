<?php

return [

    /*
    |--------------------------------------------------------------------------
    | sms Defaults
    |--------------------------------------------------------------------------
    |
    | 短信配置
    |
    */
    'default'  => env('SMS', 'cl'),

    'sms_switch'  => env('SMS_SWITCH', false),    // 手机短信发送开关，本地开发 false,关闭

    /*
    |--------------------------------------------------------------------------
    | 创蓝短信设置
    |--------------------------------------------------------------------------
    | 
    | 国内短信，国际短信
    | Supported: "internal", "external"
    |
    */
    'cl' => [
        'internal' => [
            'account'   => env('SMS_ACCOUNT', '1'),    //账号
            'password'  => env('SMS_PASSWORD', '1'),  //密码
            'max'       => env('SMS_MAX', 10),               //每日限制发送短信数量
            'lifecycle' => env('SMS_LIFECYCLE', 3),           //短信有效期(单位为分钟)
            'templates' => [
                'registerCode' => "【GoldmanGlobal】您的验证码为：:code，请在三分钟内输入。为了保障您的账户安全，请勿将验证码短信转发他人",
                'backPassCode' => "【GoldmanGlobal】您的验证码为：:pass，请在三分钟内输入。为了保障您的账户安全，请勿将验证码短信转发他人",
                'modifyMobile' => "【GoldmanGlobal】您的验证码为：:mobileCode，请在三分钟内输入。为了保障您的账户安全，请勿将验证码短信转发他人",
            ],
        ],
        'external' => [      //国际短信
            'account'   => env('SMS_ACCOUNT_EXT', ''),    //账号
            'password'  => env('SMS_PASSWORD_EXT', ''),  //密码
            'max'       => env('SMS_MAX', 10),               //每日限制发送短信数量
            'lifecycle' => env('SMS_LIFECYCLE', 3),           //短信有效期(单位为分钟)
            'templates' => [
                'registerCode' => "Your verification code is：:code，Please enter it within three minutes. In order to keep your account secure, please do not forward the verification code SMS to others.",
                'backPassCode' => "Your verification code is：:pass，Please enter it within three minutes. In order to keep your account secure, please do not forward the verification code SMS to others.",
                'modifyMobile' => "Your verification code is：:mobileCode，Please enter it within three minutes. In order to keep your account secure, please do not forward the verification code SMS to others.",
            ],
        ],
    ],
];