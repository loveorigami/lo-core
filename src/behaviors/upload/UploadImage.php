<?php

namespace lo\core\behaviors\upload;

use abeautifulsite\SimpleImage;
use lo\core\interfaces\IUploadImage;
use mongosoft\file\UploadImageBehavior;
use yii\helpers\ArrayHelper;

class UploadImage extends UploadImageBehavior implements IUploadImage
{
    /** @var boolean */
    public $createThumbs = true;

    /** @var boolean */
    public $createThumbsOnSave = false;

    /** @var boolean */
    public $createThumbsOnRequest = true;

    /**
     * @param string $attribute
     * @param string $profile
     * @param boolean $old
     * @return string
     */
    public function getThumbUploadPath($attribute, $profile = 'thumb', $old = false)
    {
        if (!$this->thumbPath) {
            return $this->getUploadPath($attribute);
        }
        return parent::getThumbUploadPath($attribute, $profile = 'thumb', $old = false);
    }

    /**
     * @param string $attribute
     * @param string $profile
     * @return string|null
     */
    public function getThumbUploadUrl($attribute, $profile = 'thumb')
    {
        if (!$this->thumbUrl) {
            return $this->getUploadUrl($attribute);
        }
        return parent::getThumbUploadUrl($attribute, $profile = 'thumb');
    }

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