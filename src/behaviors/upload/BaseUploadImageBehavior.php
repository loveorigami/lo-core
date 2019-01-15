<?php

namespace lo\core\behaviors\upload;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * UploadImageBehavior automatically uploads image, creates thumbnails and fills
 * the specified attribute with a value of the name of the uploaded image.
 *
 * To use UploadImageBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use mongosoft\file\UploadImageBehavior;
 *
 * function behaviors()
 * {
 *     return [
 *         [
 *             'class' => UploadImageBehavior::className(),
 *             'attribute' => 'file',
 *             'scenarios' => ['insert', 'update'],
 *             'placeholder' => '@app/modules/user/assets/images/userpic.jpg',
 *             'path' => '@webroot/upload/{id}/images',
 *             'url' => '@web/upload/{id}/images',
 *             'thumbPath' => '@webroot/upload/{id}/images/thumb',
 *             'thumbUrl' => '@web/upload/{id}/images/thumb',
 *             'thumbs' => [
 *                   'thumb' => ['width' => 400, 'quality' => 90],
 *                   'preview' => ['width' => 200, 'height' => 200],
 *              ],
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Alexander Mohorev <dev.mohorev@gmail.com>
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */
class BaseUploadImageBehavior extends BaseUploadBehavior
{
    /**
     * @var string
     */
    public $placeholder;
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
    public function init(): void
    {
        parent::init();

        if ($this->createThumbsOnSave) {
            if ($this->thumbPath === null) {
                $this->thumbPath = $this->path;
            }
            if ($this->thumbUrl === null) {
                $this->thumbUrl = $this->url;
            }

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
    protected function afterUpload(): void
    {
        parent::afterUpload();
        if ($this->createThumbsOnSave) {
            $this->createThumbs();
        }
    }

    /**
     * @throws \yii\base\Exception
     */
    protected function createThumbs(): void
    {
        $path = $this->getUploadPath($this->attribute);
        foreach ($this->thumbs as $profile => $config) {
            $thumbPath = $this->getThumbUploadPath($this->attribute, $profile);
            if ($thumbPath !== null) {
                if (!FileHelper::createDirectory(dirname($thumbPath))) {
                    throw new InvalidArgumentException("Directory specified in 'thumbPath' attribute doesn't exist or cannot be created.");
                }
                if (!is_file($thumbPath)) {
                    $this->generateImageThumb($config, $path, $thumbPath);
                }
            }
        }
    }

    /**
     * @param string  $attribute
     * @param string  $profile
     * @param boolean $old
     * @return string
     */
    public function getThumbUploadPath($attribute, $profile = 'thumb', $old = false): ?string
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        $path = $this->resolvePath($this->thumbPath);
        $attribute = ($old === true) ? $model->getOldAttribute($attribute) : $model->$attribute;
        $filename = $this->getThumbFileName($attribute, $profile);

        return $filename ? Yii::getAlias($path . '/' . $filename) : null;
    }

    /**
     * @param string $attribute
     * @param string $profile
     * @return string|null
     * @throws \yii\base\Exception
     */
    public function getThumbUploadUrl($attribute, $profile = 'thumb'): ?string
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        $path = $this->getUploadPath($attribute, true);
        if (is_file($path)) {
            if ($this->createThumbsOnRequest) {
                $this->createThumbs();
            }
            $url = $this->resolvePath($this->thumbUrl);
            $fileName = $model->getOldAttribute($attribute);
            $thumbName = $this->getThumbFileName($fileName, $profile);

            return Yii::getAlias($url . '/' . $thumbName);
        }

        if ($this->placeholder) {
            return $this->getPlaceholderUrl($profile);
        }

        return null;
    }

    /**
     * @param $profile
     * @return string
     */
    protected function getPlaceholderUrl($profile): ?string
    {
        [$path, $url] = Yii::$app->assetManager->publish($this->placeholder);
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
    protected function delete($attribute, $old = false): void
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
     * @param        $filename
     * @param string $profile
     * @return string
     */
    protected function getThumbFileName($filename, $profile = 'thumb'): string
    {
        return $profile . '-' . $filename;
    }

    /**
     * @param $config
     * @param $path
     * @param $thumbPath
     */
    protected function generateImageThumb($config, $path, $thumbPath): void
    {

    }
}
