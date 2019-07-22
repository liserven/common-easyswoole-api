<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-03-24
 * Time: 10:02
 */

namespace App\Lib\Process;

use App\Lib\Redis\Redis;
use EasySwoole\Component\Process\AbstractProcess;
use Swoole\Process;

class ConsumerText extends AbstractProcess
{
    private $isRun = false;
    public function run($arg)
    {
        // TODO: Implement run() method.
        /*
         * 举例，消费redis中的队列数据
         * 定时500ms检测有没有任务，有的话就while死循环执行
         */
        $this->addTick(2000,function (){
            echo '任务开始';
            if(!$this->isRun){
                $this->isRun = true;
                while (true){
                    try{
                        $task = Redis::getInstance()->lPop('li_list_text');
                        if($task){
                            echo '有消费者进入';
                            // do you task
                        }else{
                            echo '暂无消费者进入';
                            break;
                        }
                    }catch (\Throwable $throwable){
                        var_dump($throwable->getMessage());
                        break;
                    }
                }
                $this->isRun = false;
            }
            var_dump($this->getProcessName().' task run check');
        });
    }

    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }

    public function onReceive(string $str, ...$args)
    {
        // TODO: Implement onReceive() method.
    }
}
