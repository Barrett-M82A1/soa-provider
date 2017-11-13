<?php
/**
 * 启动服务
 */

//composer自动加载
require_once __DIR__.'/vendor/autoload.php';

//服务配置
$config = include __DIR__."/config/config.php";

//启动服务
$server = new \swoole_server($config['ip'], $config['port']);

$server->set($config['swoole']);

$server->on('connect', function ($server, $fd) {
    echo "connection open: {$fd}\n";
});

$server->on('start','onStart');
$server->on('receive','onReceive');

$server->on('close', function ($server, $fd) {
    echo "connection close: {$fd}\n";
});

$server->start();

/**
 * 启动操作 - 服务中心注册
 * @param swoole_server $server
 */
function onStart(\swoole_server $server)
{
    #这里还要实现配置中心选择的算法

    //服务注册
    $client = new \swoole_client(SWOOLE_SOCK_TCP);
    $client->connect($config['mysoa'][0]['ip'],$config['mysoa'][0]['port'], 0.5);

    //设置注册信息
    $msg = json_encode(array_merge($config['service'],['method'=>'register']));
    $str = pack('N', strlen($msg)) . $msg;

    //提交注册
    $client->send($str);
}

/**
 * 收到请求处理业务
 * @param swoole_server $server
 * @param int $fd
 * @param int $reactor_id
 * @param string $data
 */
function onReceive(\swoole_server $server, int $fd, int $reactor_id, string $data)
{
    //反序列化参数
    $param = json_decode(substr($data, 4),true);

    #根据参数去调用相关logic

    //输出结果
    $response = json_encode(['uid' => 1000]);
    $len = strlen($response);

    $content = pack('N', $len) . $response;
    $server->send($fd, $content);
}
