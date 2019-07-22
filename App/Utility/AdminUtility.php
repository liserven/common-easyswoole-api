<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-06-26
 * Time: 14:00
 */

namespace App\Utility;


use App\Model\Admin;
use EasySwoole\Component\Context\ContextManager;
use EasySwoole\EasySwoole\ServerManager;

class AdminUtility
{

    /**
     * 获取用户的真实IP
     * @param string $headerName 代理服务器传递的标头名称
     * @return string
     */
    public static function clientRealIP($headerName = 'x-real-ip')
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $request = ContextManager::getInstance()->get('Request');
        $client = $server->getClientInfo($request->getSwooleRequest()->fd);
        $clientAddress = $client['remote_ip'];
        $xri = $request->getHeader($headerName);
        $xff = $request->getHeader('x-forwarded-for');
        if ($clientAddress === '127.0.0.1') {
            if (!empty($xri)) {  // 如果有xri 则判定为前端有NGINX等代理
                $clientAddress = $xri[0];
            } elseif (!empty($xff)) {  // 如果不存在xri 则继续判断xff
                $list = explode(',', $xff[0]);
                if (isset($list[0])) $clientAddress = $list[0];
            }
        }
        return $clientAddress;
    }


    public static function getAdminInfo($key = '')
    {
        $request = ContextManager::getInstance()->get('Request');
        $response  = ContextManager::getInstance()->get('Response');
        if (!$request->hasHeader('token')) {
            $data = [
                'bol' => false,
                'msg' => '未登陆',
                'code' => 10005  ,
                'data' => []
            ];
            $response->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $response->withHeader('Content-type', 'application/json;charset=utf-8');
            $response->withStatus(200);
            $response->end();
        }
        $token = $request->getHeader('token');
        $userInfo = json_decode(Redis::getInstance()->get($token[0]), true);
        if (!$userInfo) {
            $data = [
                'bol' => false,
                'msg' => '登陆已过期',
                'code' => 10005  ,
                'data' => []
            ];
            $response->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $response->withHeader('Content-type', 'application/json;charset=utf-8');
            $response->withStatus(200);
            $response->end();
        }
        $userData = (new Admin())->getFind(['id' => $userInfo['id']]);
//        if ($userData['token'] != $token[0]) {
//            $data = [
//                'bol' => false,
//                'msg' => '在其他设备登陆',
//                'code' => 10005  ,
//                'data' => []
//            ];
//            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
//            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
//            $this->response()->withStatus(200);
//            $this->response()->end();
//        }

        return !empty($key) ? $userData[$key] : $userData;
    }


    public static function returnJson($msg = 'ok', $data = [] , $bol = true  , $code = '0'){
        $data = [
            'bol' => $bol,
            'msg' => $msg,
            'code' => $code  ,
            'data' => $data
        ];
        $response  = ContextManager::getInstance()->get('Response');
        $response->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $response->withHeader('Content-type', 'application/json;charset=utf-8');
        $response->withStatus(200);
        $response->end();
    }
}
