<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-03-21
 * Time: 12:27
 */

namespace App\Lib\Redis;

use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Config;

class Redis
{
    use Singleton;

    public $redis = null;

    private function __construct()
    {
        if( !extension_loaded('redis') )
        {
            throw new \Exception('Redis 扩展不存在');
        }

        try{
            $redisConfig    = \YaConf::get('redis');
            $this->redis    = new \Redis();
            $result         = $this->redis->connect($redisConfig['host'], intval($redisConfig['port']),
                intval($redisConfig['time_out']));
        }catch ( \Exception $e){
            throw new \Exception($e->getMessage());
        }

        if( $result === false )
        {
            throw new \Exception('Redis 连接失败');
        }

    }



    public function get($key )
    {
        if( empty($key))
        {
            return '';
        }
        return $this->redis->get($key);
    }

    public function expire($key, $time)
    {
        if( empty($key))
        {
            return '';
        }
        return $this->redis->expire($key, $time);
    }

    public function set($key , $val)
    {
        if( empty($key) || empty($val))
        {
            return false;
        }
        return $this->redis->set($key, $val);
    }

    public function lPop($key )
    {
        if( empty($key))
        {
            return '';
        }
        return $this->redis->lPop($key);
    }
    public function lRange($key, $start = 0 , $over = -1 )
    {
        if( empty($key))
        {
            return '';
        }
        return $this->redis->lRange($key, $start, $over);
    }


    public function hSet(string $key, array $data)
    {
        if( empty($key))
        {
            return '';
        }
        return $this->redis->hSet('user_token', $key,$data);
    }
    public function hMGet(string $key)
    {
        if( empty($key))
        {
            return [];
        }
        return $this->redis->hMGet('user_token',$key);
    }
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        $this->redis->$name($arguments);


    }
}
