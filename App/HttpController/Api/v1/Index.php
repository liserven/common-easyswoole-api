<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 20:09
 */

namespace App\HttpController\Api\v1;


use App\HttpController\Base;

class Index extends Base
{
    public function test()
    {

        $config = \YaConf::get('mysql');
        var_dump($config);
    }
}
