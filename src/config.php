<?php

/**
 * 配置文件
 *
 * @author yzh52521<396751927@qq.com>
 * @copyright yzh52521
 */

return [
    /*
      * 默认配置，将会合并到各模块中
      */
    'default'         => [
        /*
         * 指定 API 调用返回结果的类型：array(default)/object/raw/自定义类名
         */
        'response_type' => 'array',
        /*
         * 使用 ThinkPHP 的缓存系统
         */
        'use_tp_cache'  => true,
        /*
         * 日志配置
         *
         * level: 日志级别，可选为：
         *                 debug/info/notice/warning/error/critical/alert/emergency
         * file：日志文件位置(绝对路径!!!)，要求可写权限
         */
        'log'           => [
            'default'  => 'default',
            'channels' => [
                'thinkphp' => [
                    'driver' => 'thinkphp',
                    'level'  => env('WECHAT.WECHAT_LOG_LEVEL', 'debug'),
                ],
                'default'  => [
                    'driver' => 'daily',
                    'level'  => env('WECHAT.WECHAT_LOG_LEVEL', 'debug'),
                    'path'   => env('WECHAT.WECHAT_LOG_FILE', app()->getRuntimePath() . "log/wechat.log"),
                ],
            ],
        ],
    ],

    //公众号
    'official_account' => [
        'default' => [
            // AppID
            'app_id' => env('WECHAT.WECHAT_OFFICIAL_ACCOUNT_APPID', 'your-app-id'),
            // AppSecret
            'secret' => env('WECHAT.WECHAT_OFFICIAL_ACCOUNT_SECRET', 'your-app-secret'),
            // Token
            'token' => env('WECHAT.WECHAT_OFFICIAL_ACCOUNT_TOKEN', 'your-token'),
            // EncodingAESKey
            'aes_key' => env('WECHAT.WECHAT_OFFICIAL_ACCOUNT_AES_KEY', ''),
            /*
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
             */
            //'oauth' => [
            //    'scopes'   => array_map('trim',
            //        explode(',', env('WECHAT.WECHAT_OFFICIAL_ACCOUNT_OAUTH_SCOPES', 'snsapi_userinfo'))),
            //    'callback' => env('WECHAT.WECHAT_OFFICIAL_ACCOUNT_OAUTH_CALLBACK', '/examples/oauth_callback.php'),
            //],
        ],
    ],

    //第三方开发平台
    //'open_platform'    => [
    //    'default' => [
    //        'app_id'  => env('WECHAT.WECHAT_OPEN_PLATFORM_APPID', ''),
    //        'secret'  => env('WECHAT.WECHAT_OPEN_PLATFORM_SECRET', ''),
    //        'token'   => env('WECHAT.WECHAT_OPEN_PLATFORM_TOKEN', ''),
    //        'aes_key' => env('WECHAT.WECHAT_OPEN_PLATFORM_AES_KEY', ''),
    //    ],
    //],

    //小程序
    //'mini_app'     => [
    //    'default' => [
    //        'app_id'  => env('WECHAT.WECHAT_MINI_PROGRAM_APPID', ''),
    //        'secret'  => env('WECHAT.WECHAT_MINI_PROGRAM_SECRET', ''),
    //        'token'   => env('WECHAT.WECHAT_MINI_PROGRAM_TOKEN', ''),
    //        'aes_key' => env('WECHAT.WECHAT_MINI_PROGRAM_AES_KEY', ''),
    //    ],
    //],

    //支付
    'payment'          => [
        'default' => [
            'app_id'     => env('WECHAT.WECHAT_PAYMENT_APPID', ''),
            'mch_id'     => env('WECHAT.WECHAT_PAYMENT_MCH_ID', 'your-mch-id'),
            'secret_key'        => env('WECHAT.WECHAT_PAYMENT_KEY', 'key-for-signature'),
            'v2_secret_key'        => env('WECHAT.WECHAT_PAYMENT_V2_KEY', 'key-for-signature'),
            // 商户证书: 
            // root_path/certs/1123123123/application_cert.pem
            // root_path/certs/1123123123/application_key.pem
            'certificate'  => root_path('certs') .  env('WECHAT.WECHAT_PAYMENT_CERT_PATH', env('WECHAT.WECHAT_PAYMENT_MCH_ID', 'your-mch-id') . 'apiclient_cert.pem'),    // XXX: 绝对路径！！！！
            'private_key'   => root_path('certs').  env('WECHAT.WECHAT_PAYMENT_KEY_PATH', env('WECHAT.WECHAT_PAYMENT_MCH_ID', 'your-mch-id') . 'apiclient_key.pem'),      // XXX: 绝对路径！！！！
            'notify_url' => env('WECHAT_PAYMENT_NOTIFY_URL','http://example.com/payments/wechat-notify'),                           // 默认支付结果通知地址

            // 平台证书：微信支付 APIv3 平台证书，需要使用工具下载
            // 下载工具：https://github.com/wechatpay-apiv3/CertificateDownloader
            'platform_certs' => [
                // '/path/to/wechatpay/cert.pem',
            ],

            /**
             * 接口请求相关配置，超时时间等，具体可用参数请参考：
             * https://github.com/symfony/symfony/blob/5.3/src/Symfony/Contracts/HttpClient/HttpClientInterface.php
             */
            // 'http' => [
            //     // 'throw'  => true, // 状态码非 200、300 时是否抛出异常，默认为开启
            //     // 'timeout' => 5.0,
            //     // 'base_uri' => 'https://api.mch.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri
            // ],
        ],
        // ...
    ],

    //企业微信
    //'work'             => [
    //    'default' => [
    //        'corp_id'  => 'xxxxxxxxxxxxxxxxx',
    //        'agent_id' => 100020,
    //        'secret'   => env('WECHAT.WECHAT_WORK_AGENT_CONTACTS_SECRET', ''),
    //        //...
    //    ],
    //],


    //企业开放平台
    //'open_work'             => [
    //    'default' => [
    //        //参考EasyWechat官方文档
    //        //https://www.easywechat.com/docs/4.1/open-work/index
    //    ],
    //],

    //是否注入框架日志驱动
    'inject_think_logger' => true,
];
