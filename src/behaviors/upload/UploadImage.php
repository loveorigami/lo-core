<?php

namespace lo\core\behaviors\upload;

use abeautifulsite\SimpleImage;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class UploadImage extends UploadFile
{
    /**
     * @var boolean
     */
    public $createThumbsOnSave = true;
    /**
     * @var boolean
     */
    public $createThumbsOnRequest = false;

    /**
     * @var array the thumbnail profiles
     * - `width`
     * - `height`
     * - `quality`
     */
    public $thumbs = [
        'thumb' => ['width' => 200, 'height' => 200, 'quality' => 90],
    ];

    /**
     * @var string|null
     */
    public $thumbPath;
    /**
     * @var string|null
     */
    public $thumbUrl;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->createThumbsOnSave) {
            if ($this->thumbPath === null) {
                $this->thumbPath = $this->storagePath;
            }
            if ($this->thumbUrl === null) {
                $this->thumbUrl = $this->storageUrl;
            }
            foreach ($this->thumbs as $config) {
                $width = ArrayHelper::getValue($config, 'width');
                $height = ArrayHelper::getValue($config, 'height');
                if ($height < 1 && $width < 1) {
                    throw new InvalidConfigException(sprintf(
                        'Length of either side of thumb cannot be 0 or negative, current size '.
                        'is %sx%s', $width, $height
                    ));
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function afterUpload()
    {
        parent::afterUpload();
        if ($this->createThumbsOnSave == true) {
            $this->createThumbs();
        }
    }

    /**
     * @throws \yii\base\InvalidParamException
     */
    protected function createThumbs()
    {
/*        $path = $this->getUploadPath($this->attribute);
        foreach ($this->thumbs as $profile => $config) {
            $thumbPath = $this->getThumbUploadPath($this->attribute, $profile);
            if ($thumbPath !== null) {
                if (!FileHelper::createDirectory(dirname($thumbPath))) {
                    throw new InvalidParamException("Directory specified in 'thumbPath' attribute doesn't exist or cannot be created.");
                }
                if (!is_file($thumbPath)) {
                    $this->generateImageThumb($config, $path, $thumbPath);
                }
            }
        }*/
        $path = $this->getUploadPath($this->attribute);
        echo $path;
        //$img = new SimpleImage($this->storagePath . DIRECTORY_SEPARATOR . $path);
        //$img->thumbnail(100, 75)->save($this->storagePath . DIRECTORY_SEPARATOR . 'thumb_' . $path, 90);
    }

}