<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 20:09
 */

namespace App\HttpController;


use EasySwoole\Component\Context\ContextManager;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;



class Base extends Controller
{

    protected function onException(\Throwable $throwable): void
    {
        $response = ContextManager::getInstance()->get('Response');
        $data = [
            'error_code'        => $throwable->getCode(),
            'msg'               => $throwable->getMessage(),
            'bol'               => false,
            'data'              => [],
        ];
        if( $throwable instanceof  \Exception ){
            //如果异常是自己抛出的
            $response->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $response->withHeader('Content-type', 'application/json;charset=utf-8');
            $response->withStatus(Status::CODE_INTERNAL_SERVER_ERROR);
            $this->response()->end();
        }else{
            //否则还抛系统异常
            parent::onException($throwable); // TODO: Change the autogenerated stub

        }
    }

    public function __hook(?string $actionName, Request $request, Response $response)
    {
        $context = ContextManager::getInstance();
        $context->set('Request', $request);
        $context->set('Response', $response);
        return parent::__hook($actionName, $request, $response); // TODO: Change the autogenerated stub
    }


    public function getUserInfo($token =null )
    {

    }

    public function index()
    {
        // TODO: Implement index() method.
    }
}
