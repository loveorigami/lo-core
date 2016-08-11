<?php

namespace lo\core\behaviors\upload;

use Yii;
use yii\base\Behavior;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;

class Upload extends Behavior
{
    /**
     * Behavior for simplifies file upload
     *
     * For example:
     *
     * ```php
     * public function behaviors()
     * {
     *      return [
     *          'file' => [
     *              'class' => UploadFileBehavior::className(),
     *              'attributeName' => 'picture',
     *              'savePath' => '@webroot/uploads',
     *              'generateNewName' => true,
     *              'protectOldValue' => true,
     *          ],
     *      ];
     * }
     * ```
     *
     * @author HimikLab
     */

    /** @var string model file field name */
    public $attributeName = '';

    /**
     * @var string|callable path or alias to the directory in which to save files
     * or anonymous function returns directory path
     */
    public $savePath = '';

    /**
     * @var bool|callable generate a new unique name for the file
     * set true (@see self::generateFileName()) or anonymous function takes the old file name and returns a new name
     */
    public $generateNewName = false;

    /** @var bool erase protection the old value of the model attribute if the form returns empty string */
    public $protectOldValue = false;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function init()
    {
        if ($this->savePath instanceof \Closure) {
            $this->savePath = call_user_func($this->savePath);
        }
        $this->savePath = Yii::getAlias($this->savePath);
    }

    public function beforeValidate()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        if ($file = UploadedFile::getInstance($model, $this->attributeName)) {
            $model->setAttribute($this->attributeName, $file);
        }
    }

    public function beforeInsert()
    {
        $this->loadFile();
    }

    public function beforeUpdate()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        if ($model->getAttribute($this->attributeName) !== '') {
            $this->loadFile();
            return;
        }
        if ($this->protectOldValue) {
            $model->setAttribute(
                $this->attributeName,
                $model->getOldAttribute($this->attributeName)
            );
        }
    }

    public function beforeDelete()
    {
        $this->deleteFile();
    }

    protected function loadFile()
    {
        // delete the old version if it necessary
        $this->deleteFile();
        /** @var ActiveRecord $model */
        /** @var UploadedFile $file */
        $model = $this->owner;
        $file = $model->getAttribute($this->attributeName);

        if (!($file instanceof UploadedFile)) {
            return;
        }

        $fileName = $file->name;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0755, true);
        }

        if ($this->generateNewName !== false) {
            $fileName = $this->generateNewName instanceof \Closure ?
                call_user_func($this->generateNewName, $fileName) :
                $this->generateFileName($file);
            $file->name = $fileName;
        }

        $file->saveAs($this->savePath . DIRECTORY_SEPARATOR . $fileName);
        Image::thumbnail($this->savePath . DIRECTORY_SEPARATOR . $fileName, 160, 90)
            ->save($this->savePath . DIRECTORY_SEPARATOR . 'thumb_' . $fileName, ['quality' => 90]);

        $model->setAttributes([$this->attributeName => $file]);
    }

    protected function deleteFile()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        if (!$oldFileName = $model->getOldAttribute($this->attributeName)) {
            return;
        }
        $filePath = $this->savePath . DIRECTORY_SEPARATOR . $oldFileName;
        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

    protected function generateFileName(UploadedFile $file)
    {
        return uniqid() . '.' . $file->getExtension();
    }
}