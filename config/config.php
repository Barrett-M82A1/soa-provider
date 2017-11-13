<?php
/**
 * 服务配置
 */
return [

    //提供者配置
    'service'   =>  [
        //提供者名称
        'authors_name'  =>  '38923',

        //提供者帐号
        'account'   =>  'i@yoyoyo.me',

        //提供者IP
        'ip'        => '172.17.0.3',

        //端口
        'port'      => 8000,

        //服务中心回调端口
        'notify_port'   => 8090,

        //超时响应时间（ms）
        'out_time'  =>  2000,

        //提供的服务
        'name'  => [

            //服务A
            'UserService',

            //服务B
            'StudentUserService'
        ],
    ],

    //服务中心配置
    'mysoa'     => [
        [
            //服务中心IP
            'ip'    =>  '172.17.0.1',

            //服务中心端口
            'port'  =>  8081
        ],[
            //服务中心IP
            'ip'    =>  '172.17.0.2',

            //服务中心端口
            'port'  =>  8081
        ],
    ],

    //需要消费的服务
    'consumer'  =>  [
        'MarketService',
        'ScoreService',
        'MessageService'
    ],

    //swoole配置
    'swoole'    =>  [
        'worker_num'            => 1,
        'open_length_check'     => true,        // 开启协议解析
        'package_length_type'   => 'N',         // 长度字段的类型
        'package_length_offset' => 0,           // 第4个字节开始是包长度
        'package_body_offset'   => 4,           // 第8个字节开始计算长度
        'package_max_length'    => 2000000,     // 协议最大长度
    ]
];