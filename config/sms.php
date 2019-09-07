<?php

return [

    /*
    |--------------------------------------------------------------------------
    | sms Defaults
    |--------------------------------------------------------------------------
    |
    | 短信配置
    |
    | 支持：创蓝 cl
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
            'max'       => env('SMS_MAX', 10),               //每日限制发送短信数量, 0或空表不限制
            'lifecycle' => env('SMS_LIFECYCLE', 120),           //短信有效期(单位为秒), 0或空表不限制
        ],
        'external' => [      //国际短信
            'account'   => env('SMS_ACCOUNT_EXT', ''),    //账号
            'password'  => env('SMS_PASSWORD_EXT', ''),  //密码
            'max'       => env('SMS_MAX', 10),               //每日限制发送短信数量, 0或空表不限制
            'lifecycle' => env('SMS_LIFECYCLE', 120),           //短信有效期(单位为秒), 0或空表不限制
        ],
        //模板配置, 也可自行放数据库里,通过Sms::server()->setConfig()覆盖配置
        'templates' => [
            //按手机区号发短信: 86国内,852 香港,886 台湾,1 美国 ...
            //支持多个使用同一模板,以,分隔,如香港/台湾同为繁体中文, 852,886
            //支持区号在多个区号串中，对同模板key的取最后一条
            'default'   => '86',//默认短信语言,不存在区号对应短信时使用,也可以使用其它键名如default
            'list'      => [
                '86'        => [
                    'registerCode' => "【xxx】您的验证码为：:code，请在三分钟内输入。为了保障您的账户安全，请勿将验证码短信转发他人",
                    'backPassCode' => "【xxx】您的验证码为：:pass，请在三分钟内输入。为了保障您的账户安全，请勿将验证码短信转发他人",
                    'modifyMobile' => "【xxx】您的验证码为：:mobileCode，请在三分钟内输入。为了保障您的账户安全，请勿将验证码短信转发他人",
                ],
                '852,886'        => [
                    'registerCode' => "【xxx】你的验证码为：：code，请喺三分钟内输入。为咗保障你嘅账户安全，请勿将验证码短讯转发佢人",
                    'backPassCode' => "【xxx】你的验证码为：:pass，请喺三分钟内输入。为咗保障你嘅账户安全，请勿将验证码短讯转发佢人",
                    'modifyMobile' => "【xxx】你的验证码为：:mobileCode，请喺三分钟内输入。为咗保障你嘅账户安全，请勿将验证码短讯转发佢人",
                ],
                '1'         => [
                    'registerCode' => "Your verification code is：:code，Please enter it within three minutes. In order to keep your account secure, please do not forward the verification code SMS to others.",
                    'backPassCode' => "Your verification code is：:pass，Please enter it within three minutes. In order to keep your account secure, please do not forward the verification code SMS to others.",
                    'modifyMobile' => "Your verification code is：:mobileCode，Please enter it within three minutes. In order to keep your account secure, please do not forward the verification code SMS to others.",
                ],
            ],
        ]
    ],
];