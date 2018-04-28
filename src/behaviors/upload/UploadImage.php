<?php

namespace lo\core\behaviors\upload;

use claviska\SimpleImage;
use yii\helpers\ArrayHelper;

class UploadImage extends BaseUploadImageBehavior implements IUploadImage
{
    /** @var boolean */
    public $createThumbs = true;

    /** @var boolean */
    public $createThumbsOnSave = false;

    /** @var boolean */
    public $createThumbsOnRequest = true;

    /** @var boolean */
    public $instanceByName = false;


    /**
     * @param string $attribute
     * @param string $profile
     * @param boolean $old
     * @return string
     */
    public function getThumbUploadPath($attribute, $profile = 'tmb', $old = false)
    {
        if (!$this->thumbPath) {
            return $this->getUploadPath($attribute);
        }
        return parent::getThumbUploadPath($attribute, $profile, $old = false);
    }

    /**
     * @param string $attribute
     * @param string $profile
     * @return string|null
     * @throws \yii\base\Exception
     */
    public function getThumbUploadUrl($attribute, $profile = 'tmb')
    {
        if (!$this->thumbUrl) {
            return $this->getUploadUrl($attribute);
        }
        return parent::getThumbUploadUrl($attribute, $profile);
    }

    /**
     * @param $config
     * @param $path
     * @param $thumbPath
     * @throws \Exception
     */
    protected function generateImageThumb($config, $path, $thumbPath)
    {
        $width = ArrayHelper::getValue($config, 'width');
        $height = ArrayHelper::getValue($config, 'height');
        $quality = ArrayHelper::getValue($config, 'quality', 100);

        $img = new SimpleImage($path);
        $img->thumbnail($width, $height)->toFile($thumbPath, null, $quality);
    }
}