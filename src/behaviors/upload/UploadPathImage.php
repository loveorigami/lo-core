<?php

namespace lo\core\behaviors\upload;

use abeautifulsite\SimpleImage;
use lo\core\interfaces\IUploadImage;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

class UploadPathImage extends UploadPathFile implements IUploadImage
{
    /** @var string */
    public $placeholder;

    /** @var boolean */
    public $createThumbs = true;

    /** @var boolean */
    public $createThumbsOnSave = false;

    /** @var boolean */
    public $createThumbsOnRequest = true;

    /**
     * @var array the thumbnail profiles
     * - `width`
     * - `height`
     * - `quality`
     */
    public $thumbs = [
        'thumb' => ['width' => 200, 'height' => 200, 'quality' => 90],
    ];

    /** @var string|null */
    public $thumbPath;

    /** @var string|null */
    public $thumbUrl;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->createThumbsOnSave && $this->thumbPath) {
            foreach ($this->thumbs as $config) {
                $width = ArrayHelper::getValue($config, 'width');
                $height = ArrayHelper::getValue($config, 'height');
                if ($height < 1 && $width < 1) {
                    throw new InvalidConfigException(sprintf(
                        'Length of either side of thumb cannot be 0 or negative, current size ' .
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
        if ($this->createThumbsOnSave && $this->thumbPath) {
            $this->createThumbs();
        }
    }

    /**
     * @throws \yii\base\InvalidParamException
     */
    protected function createThumbs()
    {
        $path = $this->getUploadPath($this->attribute);

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
        }
    }

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

        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        $path = $this->thumbPath;

        $attribute = ($old === true) ? $model->getOldAttribute($attribute) : $model->$attribute;
        $attribute = basename($attribute);

        $filename = $this->getThumbFileName($attribute, $profile);

        return $filename ? Yii::getAlias($path . '/' . $filename) : null;
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

        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        $path = $this->getUploadPath($attribute, true);

        if (is_file($path)) {
            if ($this->createThumbsOnRequest) {
                $this->createThumbs();
            }
            $url = $this->thumbUrl;
            $fileName = $model->getOldAttribute($attribute);
            $fileName = basename($fileName);

            $thumbName = $this->getThumbFileName($fileName, $profile);

            return Yii::getAlias($url . '/' . $thumbName);
        } elseif ($this->placeholder) {
            return $this->getPlaceholderUrl($profile);
        } else {
            return null;
        }
    }

    /**
     * @param $profile
     * @return string
     */
    protected function getPlaceholderUrl($profile)
    {
        list ($path, $url) = Yii::$app->assetManager->publish($this->placeholder);
        $filename = basename($path);
        $thumb = $this->getThumbFileName($filename, $profile);
        $thumbPath = dirname($path) . DIRECTORY_SEPARATOR . $thumb;
        $thumbUrl = dirname($url) . '/' . $thumb;

        if (!is_file($thumbPath)) {
            $this->generateImageThumb($this->thumbs[$profile], $path, $thumbPath);
        }

        return $thumbUrl;
    }

    /**
     * @inheritdoc
     */
    protected function delete($attribute, $old = false)
    {
        parent::delete($attribute, $old);

        $profiles = array_keys($this->thumbs);
        foreach ($profiles as $profile) {
            $path = $this->getThumbUploadPath($attribute, $profile, $old);
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    /**
     * @param $filename
     * @param string $profile
     * @return string
     */
    protected function getThumbFileName($filename, $profile = 'thumb')
    {
        return $profile . '-' . $filename;
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

        //echo $thumbPath;
        $img = new SimpleImage($path);
        $img->thumbnail($width, $height)->save($thumbPath, $quality);
    }
}