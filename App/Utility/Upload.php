<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 20:55
 */

namespace App\Utility;

use EasySwoole\Component\Singleton;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

require_once EASYSWOOLE_ROOT."/App/Lib/Qiniu/autoload.php";


class Upload
{

    use Singleton;

    private $ak             = '';
    private $sk             = '';
    private $bucket         = '';
    private $imgUrlPrifix   = '';
    private $config         = '';

    public function __construct()
    {
        $config             = \Yaconf::get('qiniu');
        $this->config       = $config;
        $this->ak           = $config['ak'];
        $this->sk           = $config['sk'];
        $this->bucket       = $config['bucket'];
        $this->imgUrlPrifix = $config['img_url_prefix'];
    }



    public function  uploadImg($filePath, $suffix= 'jpeg')
    {
        $auth       = new Auth($this->ak, $this->sk);
        $token      = $auth->uploadToken($this->bucket);
        $uploadMgr  = new UploadManager();
        $dirName  = date('Y')."/".date('m')."/".
            substr(md5($filePath), 0, 5).date('YmdHis').rand(0, 9999).'.'.$suffix;
        list($ret, $err)     = $uploadMgr->putFile($token, $dirName, $filePath);
        if($err !== null) {
            return null;
        } else {
            return $dirName;
        }
    }


    //删除七牛资源
    public function delzFile($key = ''){
        if( empty($key) )
        {
            throw new \Exception('删除内容为空');
        }
        $auth       = new Auth($this->ak, $this->sk);
        $bucket = new BucketManager($auth);
        $result = $bucket->delete($this->bucket, $key);
        if( $result === NULL )
        {
            return true;
        }
        return false;
    }

}
