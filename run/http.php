<?php
/**
 * HTTP服务器 - 心跳检测与基础信息查询
 */

//启动HTTP服务
$http = new swoole_http_server("127.0.0.1", 80);

$http->on('request','onRequest');

$http->start();

//处理请求
function onRequest($request, $response){
    if (isset($request->post)){
        switch ($request->post['act']){
            //心跳检测
            case 'heartbeat':
                $result = 'survival';
            break;

            //获取服务信息
            case 'info':
                $result = queryService();
            break;

            //非法请求
            default:
                $result = 'Error';
        }
        $response->end($result);
    }
}

/**
 * 获取服务信息 - 包括服务下边的方法以及参数
 * @return string
 */
function queryService()
{
    //服务配置
    $config = include __DIR__."/../config/config.php";

    $list = array_keys($config['service']['name']);

    $res = [];
    foreach ($list as $service) {
        include ROOT . "/service/" . $service . ".php";
        $class = new ReflectionClass($service);
        $methods = $class->getMethods(ReflectionMethod::IS_STATIC
            | ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $item) {
            $params = [];
            foreach ($item->getParameters() as $parameter) {
                $params[] = $parameter->getName();
            }
            $res[$service][] = [
                'method' => $item->name,
                'params' => $params,
            ];
        }
    }
    return json_encode($res);
}