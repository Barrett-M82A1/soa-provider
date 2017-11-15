<?php
/**
 * 启动服务
 */

//composer自动加载
require_once __DIR__.'/../vendor/autoload.php';

//服务配置
$config = include __DIR__."/../config/config.php";

//启动服务
$server = new \swoole_server('0.0.0.0',$config['service']['port']);

$server->on('Start','onStart');
$server->on('Connect','onConnect');
$server->on('Receive','onReceive');
$server->on('Close','onClose');

$server->start();

/**
 * 启动操作 - 服务中心注册
 * @param swoole_server $server
 */
function onStart(\swoole_server $server)
{
    #这里还要实现配置中心选择的算法
    $config = include __DIR__."/../config/config.php";

    //服务注册
    $client = new \swoole_client(SWOOLE_SOCK_TCP);
    $client->connect($config['mysoa'][0]['ip'],$config['mysoa'][0]['port'], 0.5);

    //设置注册信息
    $msg = json_encode(array_merge($config['service'],['method'=>'register']));
    $str = pack('N', strlen($msg)) . $msg;

    echo "Provider : Registering services to MySoa :)\n";

    //提交注册
    $client->send($str);
}

/**
 * 建立连接
 * @param swoole_server $server
 * @param $fd
 * @param $from_id
 */
function onConnect(\swoole_server $server, $fd, $from_id)
{
    echo "Provider : Connection open -> {$fd}\n";
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
    // 反序列化请求参数
    $param = json_decode(substr($data, 4),true);

    // 根据参数去调用相关logic
    require_once __DIR__."/../service/".$param['service'].".php";

    $Service = new $param['service']();
    $result = $Service->{$param['method']}($param['param']);
    $response = json_encode($result);
    $content = pack('N', $result) . $response;
    $server->send($fd, $content);
}

/**
 * 关闭链接
 * @param swoole_server $server
 * @param int $fd
 * @param int $reactorId
 */
function onClose(\swoole_server $server, int $fd, int $reactorId)
{
    echo "Provider : Connection close -> {$fd}\n";
}