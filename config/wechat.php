<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/12
 * Time: 15:44
 * Function:
 */

return [
    'jscode2session' => 'https://api.weixin.qq.com/sns/jscode2session',

    'mini_program' => [
        'default' => [
            'app_id' => env('WECHAT_MINI_PROGRAM_APPID', ''),
            'secret' => env('WECHAT_MINI_PROGRAM_SECRET', '')
        ]
    ],



    'payment' => [
        'default' => [
            'app_id' => env('WECHAT_PAYMENT_APPID', 'your-app-id'),
            'secret' => env('WECHAT_PAYMENT_SECRET', ''), // 非必须
            'adb_app_id' =>  env('WECHAT_PAYMENT_ADB_APPID', 'you-adb-app-id'),
            'mch_id' => env('WECHAT_PAYMENT_MCH_ID', 'your-mch-id'),
            'key' => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
            'cert_path' => env('WECHAT_PAYMENT_CERT_PATH', storage_path('cert/api_client_cert.pem')),    // XXX: 绝对路径！！！！
            'key_path' => env('WECHAT_PAYMENT_KEY_PATH', storage_path('cert/api_client_key.pem')),      // XXX: 绝对路径！！！！
            'notify_url' => 'http://example.com/payments/wechat-notify',                           // 默认支付结果通知地址
        ]
    ]


];