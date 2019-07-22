<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 20:53
 */

namespace App\HttpController\Api\v1;


use App\HttpController\Base;
use EasySwoole\Http\Message\Status;

class Upload extends Base
{

    public function uploadImg()
    {
        $conf = \Yaconf::get('qiniu');
        $request = $this->request();
        $vodel = $request->getUploadedFile('file');
        if( !$vodel )
        {
            return $this->writeJson(Status::CODE_OK, [], '上传对象不存在');
        }
        $arr = [
            'image/png',
            'image/jpg',
            'image/jpeg',
        ];
        $imageType = mime_content_type($vodel->getTempName());
        if( !in_array($imageType, $arr)){
            return $this->writeJson(Status::CODE_OK, [], '上传类型不支持');
        }

        $temp = $vodel->getTempName();
        $clientMediaType = $vodel->getClientMediaType();
        $typeArr = explode('/', $clientMediaType);
        $str = (new \App\Lib\Upload($conf))->uploadImg($temp, $typeArr[1]);
        $data = Array(
            "url" => 'http://img.7dangdang.com/'.$str,
            "errno" => 0,
        );
        return $this->writeJson(Status::CODE_OK, $data, 'ok');
    }
}
