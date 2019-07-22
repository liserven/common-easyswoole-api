<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 20:28
 */

namespace App\Validator;


use EasySwoole\Component\Context\ContextManager;
use ezswoole\Validator;

class baseValidator extends Validator
{
    protected $rule=[], $message=[], $scene=[];

    public function goCheck( $data = [], $scene = 'info' )
    {

        //自定义验证类  如果验证失败直接返回错误提示信息。
        if( empty($data) )
        {
            $request = ContextManager::getInstance()->get('Request');
            $data = $request->getQueryParams() ?? $request->getParsedBody();
        }
        if( empty($scene) )
        {
            throw new \Exception('场景不存在', -1);
        }

        if( empty($this->rule) || empty($this->message))
        {
            throw new \Exception('规则缺失', -1);
        }

        $validator = $this->check($data, $this->rule, $scene);
        if( $validator !== true ){
            throw new \Exception($this->getError(), -1);
        }
    }


    protected function checkStatus($value, $rule, $data)
    {
        if( !$this->between($value, [ 1, 2 ]))
        {
            return $value.'只能存在于1,2之间';
        }
    }
}
