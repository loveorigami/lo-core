<?php

namespace lo\core\behaviors\upload;

use abeautifulsite\SimpleImage;
use mongosoft\file\UploadImageBehavior;
use yii\helpers\ArrayHelper;

class UploadImage extends UploadImageBehavior
{
    /**
     * @var boolean
     */
    public $createThumbsOnSave = false;

    /**
     * @var boolean
     */
    public $createThumbsOnRequest = true;

    /**
     * @param $config
     * @param $path
     * @param $thumbPath
     */
    protected function generateImageThumb($config, $path, $thumbPath)
    {
        $width = ArrayHelper::getValue($config, 'width');
        $height = ArrayHelper::getValue($config, 'height');
        $quality = ArrayHelper::getValue($config, 'quality', 100);

        $img = new SimpleImage($path);
        $img->thumbnail($width, $height)->save($thumbPath, $quality);
    }
}