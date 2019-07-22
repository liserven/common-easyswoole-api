<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 19:43
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;

class Index extends Controller
{
    public function index()
    {
        // TODO: Implement index() method.$
        $this->response()->withAddedHeader('Content-Type', 'text/html;charset=utf-8');
        $this->response()->write('你就是在作死');
    }
}
