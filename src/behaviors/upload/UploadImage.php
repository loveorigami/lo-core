<?php

namespace lo\core\behaviors\upload;

use claviska\SimpleImage;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class UploadImage
 *
 * @package lo\core\behaviors\upload
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
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
     * @param string  $attribute
     * @param string  $profile
     * @param boolean $old
     * @return string
     */
    public function getThumbUploadPath($attribute, $profile = 'tmb', $old = false): ?string
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
    public function getThumbUploadUrl($attribute, $profile = 'tmb'): ?string
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
    protected function generateImageThumb($config, $path, $thumbPath): void
    {
        $width = ArrayHelper::getValue($config, 'width');
        $height = ArrayHelper::getValue($config, 'height');
        $quality = ArrayHelper::getValue($config, 'quality', 100);
        $mode = ArrayHelper::getValue($config, 'mode');

        $watermark = ArrayHelper::getValue($config, 'watermark');

        $img = new SimpleImage($path);

        if ($mode === 'bestFit') {
            $img->bestFit($width, $height);
        } else {
            $img->thumbnail($width, $height);
        }

        if ($watermark instanceof \Closure) {
            $w_path = $watermark($width, $height);
        } else {
            $w_path = $watermark;
        }

        if ($w_path) {
            $img->overlay(Yii::getAlias($w_path), 'bottom right');
        }

        $img->toFile($thumbPath, null, $quality);
    }
}
