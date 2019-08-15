<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-07-07
 * Time: 20:09
 */

namespace App\HttpController\Api\v1;


use App\Bean\ImageEditorBean;
use App\HttpController\Base;
use App\Utility\ImageEditor;

class Index extends Base
{
    public function test()
    {
        $editor = new ImageEditorBean([
            'old_image' => EASYSWOOLE_ROOT. '/static/aaa.jpeg',
            'new_image' => EASYSWOOLE_ROOT. '/static/'.uniqid().'.jpeg',
            'text_config' => [
                [
                    'text' => '我爱你中国',
                    'ttf' => EASYSWOOLE_ROOT. '/static/ttf/fasong.ttf'
                ]
            ],
            'image_config' => [
                [
                    'b' => EASYSWOOLE_ROOT. '/static/qr.png',
                    'type' => 'normal',
                    'opacity' => 1,
                    'position' => 'top-right',
                    'x' => -150,
                    'y' => 50
                ]

            ]
        ]);
        $editor->imageWatermark();
        var_dump($editor->getNewImage());
    }
}
