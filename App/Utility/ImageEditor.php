<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-08-06
 * Time: 16:50
 */

namespace App\Utility;

require_once EASYSWOOLE_ROOT.'/App/Lib/grafika/src/autoloader.php';

use Grafika\Color;
use Grafika\Grafika;
use mysql_xdevapi\Exception;

class ImageEditor
{
    /**
     * @param string $image 要打水印的图片
     * @param array $config 水印的配置项目
     * @param string $newImageFile
     * @throws \Exception
     */
    public static function imageWatermark(string $image, array $config = [], $newImageFile = '' )
    {
        if( !file_exists($image) ) {
            throw new \Exception('水印图片不存在');
        }
        if( empty($newImageFile)) {
            throw new \Exception('新水印图片不存在');
        }
        $editor = Grafika::createEditor();
        $editor->open($image1, $image);
        if( isset($config['text_config'] )) {
            foreach ( $config['text_config'] as $k => $value ) {
                $text       = isset($value['text']) ? $value['text'] : '你好' ;
                $ttf        = isset( $value['ttf']) && file_exists($value['ttf'])? $value['ttf'] : EASYSWOOLE_ROOT .EASYSWOOLE_ROOT.'/static/ttf/zhongcaixingshu.ttf';
                $color      = isset($value['color']) ? $value['color'] : '#000000' ;
                $angle      = isset($value['angle']) && is_int($value['angle']) ? $value['angle'] : 0 ;
                $x          = isset($value['x']) && is_int($value['x']) && $value['x'] > 0 ? $value['x'] : 0 ;
                $y          = isset($value['y']) && is_int($value['y']) && $value['y'] > 0 ? $value['y'] : 0 ;
                $size       = isset($value['size']) && is_int($value['size']) ? $value['size'] : 12 ;
                $editor->text($image1,$text,$size,$x,$y, new Color($color), $ttf ,$angle);
            }
        }
        //如果有图片
        $result = $editor->save($image1, $newImageFile);

        if( isset($config['img_config'])) {
            self::imageImgWatermark($newImageFile, $config['img_config']);
        }
    }


    /**
     * @param $oldDImage 底部图片
     * @param array $images 上面图片 数组 可以是多张
     * images 二维数组  每个子数组里面要包含 type， opacity， position， x， y
     * @return bool|string
     * @throws \Exception
     */
    public static function imageImgWatermark ( $oldDImage , array $images = [], $newFileImage = '' ) {
        if( !empty($oldDImage) && !empty($images) ) {
            try{
                $editor = Grafika::createEditor();
                $newImgFile = $newFileImage ? $newFileImage :EASYSWOOLE_ROOT . '/static/'.uniqid().time().'.jpg';
                $editor->open($dImage , $oldDImage);
                foreach ( $images as $key => $image) {
                    if( isset($image['b']) && file_exists($image['b']) ) {
                        var_dump($images);

                        $type       = isset($image['type']) && !empty($image['type']) ? $image['type'] : 'normal';
                        $opacity    = isset($image['opacity']) && is_integer($image['opacity']) ? $image['opacity'] : 0.9;
                        $position   = isset($image['position']) && !empty($image['position']) ? $image['position']: 'center';
                        $x          = isset($image['x']) && is_int($image['x']) ? $image['x'] : 0;
                        $y          = isset($image['y']) && is_int($image['x'])  ? $image['y'] : 0;
                        $editor     ->open($bImage , $image['b']);
                        $editor     ->blend ( $dImage, $bImage , $type, $opacity, $position, $x, $y);
                    }
                }
                $editor->save($dImage,$newImgFile);
                return $newImgFile;
            }catch (\Exception $exception) {
                throw new \Exception($exception->getMessage());
            }
        }
        return false;
    }
}
