<?php
/**
 * Created by PhpStorm.
 * User: lishenyang
 * Date: 2019-08-08
 * Time: 14:02
 */

namespace App\Bean;

require_once EASYSWOOLE_ROOT.'/App/Lib/grafika/src/autoloader.php';

use EasySwoole\Spl\SplBean;
use Grafika\Color;
use Grafika\Grafika;

class ImageEditorBean extends SplBean
{
    protected $editor = null ;

    protected $text_config = [];

    protected $image_config = [];

    protected $old_image = '';

    protected $new_image = '';

    public function __construct(array $data = null, $autoCreateProperty = false)
    {
        parent::__construct($data, $autoCreateProperty);
        $this->editor = Grafika::createEditor();
    }

    /**
     * @return mixed
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * @param mixed $editor
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;
    }

    /**
     * @return array
     */
    public function getTextConfig()
    {
        return $this->text_config;
    }

    /**
     * @param array $text_config
     */
    public function setTextConfig($text_config)
    {
        $this->text_config = $text_config;
    }

    /**
     * @return array
     */
    public function getImageConfig()
    {
        return $this->image_config;
    }

    /**
     * @param array $image_config
     */
    public function setImageConfig($image_config)
    {
        $this->image_config = $image_config;
    }

    /**
     * @return mixed
     */
    public function getOldImage()
    {
        return $this->old_image;
    }

    /**
     * @param mixed $oldImage
     */
    public function setOldImage($oldImage)
    {
        $this->old_image = $oldImage;
    }

    /**
     * @return mixed
     */
    public function getNewImage()
    {
        return $this-> new_image;
    }

    /**
     * @param mixed $newImage
     */
    public function setNewImage($newImage)
    {
        $this->new_image = $newImage;
    }



    /**
     * @param string $image 要打水印的图片
     * @param array $config 水印的配置项目
     * @param string $newImageFile
     * @throws \Exception
     */
    public function imageWatermark( )
    {
        if( !file_exists($this->old_image) ) {
            throw new \Exception('水印图片不存在');
        }
        if( empty($this->new_image)) {
            throw new \Exception('新水印图片不存在');
        }
        $this->editor->open($image1, $this->old_image);
        if( isset($this->text_config ) && !empty($this->text_config)) {
            foreach ( $this->text_config as $k => $value ) {
                $text       = isset($value['text']) ? $value['text'] : '你好' ;
                $ttf        = isset( $value['ttf']) && file_exists($value['ttf'])? $value['ttf'] : EASYSWOOLE_ROOT .EASYSWOOLE_ROOT.'/static/ttf/zhongcaixingshu.ttf';
                $color      = isset($value['color']) ? $value['color'] : '#000000' ;
                $angle      = isset($value['angle']) && is_int($value['angle']) ? $value['angle'] : 0 ;
                $x          = isset($value['x']) && is_int($value['x']) && $value['x'] > 0 ? $value['x'] : 0 ;
                $y          = isset($value['y']) && is_int($value['y']) && $value['y'] > 0 ? $value['y'] : 0 ;
                $size       = isset($value['size']) && is_int($value['size']) ? $value['size'] : 12 ;
                $this->editor->text($image1,$text,$size,$x,$y, new Color($color), $ttf ,$angle);
            }
        }
        //如果有图片
        $result = $this->editor->save($image1, $this->new_image);

        if( isset($this->image_config) && !empty($this->image_config)) {
            $this->imageImgWatermark($this->new_image);
        }
    }
    /**
     * @param $oldDImage 底部图片
     * @param array $images 上面图片 数组 可以是多张
     * images 二维数组  每个子数组里面要包含 type， opacity， position， x， y
     * @return bool|string
     * @throws \Exception
     */
    public function imageImgWatermark ( $oldImageFile = '' ) {
        $oldImageFileD = !empty($oldImageFile) ? $oldImageFile : $this->old_image;
        if( !empty($oldImageFileD) && !empty($this->image_config) ) {
            try{
                $newImgFile = !empty($this->new_image) ? $this->new_image :EASYSWOOLE_ROOT . '/static/'.uniqid().time().'.jpg';
                $this->editor->open($dImage , $oldImageFileD);
                foreach ( $this->image_config as $key => $image) {
                    if( isset($image['b']) && file_exists($image['b']) ) {
                        $type       = isset($image['type']) && !empty($image['type']) ? $image['type'] : 'normal';
                        $opacity    = isset($image['opacity']) && is_integer($image['opacity']) ? $image['opacity'] : 0.9;
                        $position   = isset($image['position']) && !empty($image['position']) ? $image['position']: 'center';
                        $x          = isset($image['x']) && is_int($image['x']) ? $image['x'] : 0;
                        $y          = isset($image['y']) && is_int($image['x'])  ? $image['y'] : 0;
                        $this->editor     ->open($bImage , $image['b']);
                        $this->editor     ->blend ( $dImage, $bImage , $type, $opacity, $position, $x, $y);
                    }
                }
                $this->editor->save($dImage,$newImgFile);
                $this->new_image = $newImgFile;
            }catch (\Exception $exception) {
                throw new \Exception($exception->getMessage());
            }
        }
        return false;
    }
}
