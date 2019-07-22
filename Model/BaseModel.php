<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 19:53
 */

namespace App\Model;


class BaseModel extends \ezswoole\Model
{

    protected $returnType = 'array';

    public function checkData ($data) {
        return empty($data) ? [] : $data;
    }
}
