<?php

namespace lo\core\behaviors\upload;

use Closure;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * UploadBehavior automatically uploads file and fills the specified attribute
 * with a value of the name of the uploaded file.
 *
 * To use UploadBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use lo\core\behaviors\upload\BaseUploadBehavior;
 *
 * function behaviors()
 * {
 *     return [
 *         [
 *             'class' => BaseUploadBehavior::className(),
 *             'attribute' => 'file',
 *             'scenarios' => ['insert', 'update'],
 *             'path' => '@webroot/upload/{id}',
 *             'url' => '@web/upload/{id}',
 *         ],
 *     ];
 * }
 * ```
 *
 * @property \yii\web\UploadedFile $uploadedFile
 */
class BaseUploadBehavior extends Behavior
{
    /**
     * @event Event an event that is triggered after a file is uploaded.
     */
    protected const EVENT_AFTER_UPLOAD = 'afterUpload';

    /**
     * @var string the attribute which holds the attachment.
     */
    public $attribute;
    /**
     * @var array the scenarios in which the behavior will be triggered
     */
    public $scenarios = [];
    /**
     * @var string the base path or path alias to the directory in which to save files.
     */
    public $path;
    /**
     * @var string the base URL or path alias for this file
     */
    public $url;
    /**
     * @var bool Getting file instance by name
     */
    public $instanceByName = false;
    /**
     * @var boolean|callable generate a new unique name for the file
     * set true or anonymous function takes the old filename and returns a new name.
     * @see self::generateFileName()
     */
    public $generateNewName = true;
    /**
     * @var boolean If `true` current attribute file will be deleted
     */
    public $unlinkOnSave = true;
    /**
     * @var boolean If `true` current attribute file will be deleted after model deletion.
     */
    public $unlinkOnDelete = true;
    /**
     * @var boolean $deleteTempFile whether to delete the temporary file after saving.
     */
    public $deleteTempFile = true;

    /**
     * @var UploadedFile the uploaded file instance.
     */
    private $_file;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        if ($this->attribute === null) {
            throw new InvalidConfigException('The "attribute" property must be set.');
        }
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" property must be set.');
        }
        if ($this->url === null) {
            throw new InvalidConfigException('The "url" property must be set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * This method is invoked before validation starts.
     */
    public function beforeValidate(): void
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        $file = $model->getAttribute($this->attribute);

        if (in_array($model->scenario, $this->scenarios, true)) {

            if ($file instanceof UploadedFile) {
                $this->_file = $file;
            } elseif ($file instanceof UploadedRemoteFile) {
                $this->_file = $file;
            } elseif ($this->instanceByName === true) {
                $this->_file = UploadedFile::getInstanceByName($this->attribute);
            } else {
                $this->_file = UploadedFile::getInstance($model, $this->attribute);
            }

            if ($this->_file instanceof UploadedFile) {
                $this->_file->name = $this->getFileName($this->_file);
                $model->setAttribute($this->attribute, $this->_file);
            }

            if ($this->_file instanceof UploadedRemoteFile) {
                $this->_file->name = $this->getFileName($this->_file);
                $model->setAttribute($this->attribute, $this->_file);
            }
        }
    }

    /**
     * This method is called at the beginning of inserting or updating a record.
     */
    public function beforeSave(): void
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        if (in_array($model->scenario, $this->scenarios, true)) {
            if (
                $this->_file instanceof UploadedFile ||
                $this->_file instanceof UploadedRemoteFile
            ) {
                if ($this->unlinkOnSave === true && !$model->getIsNewRecord() && $model->isAttributeChanged($this->attribute)) {
                    $this->delete($this->attribute, true);
                }
                $model->setAttribute($this->attribute, $this->_file->name);
            } else {
                // Protect attribute
                unset($model->{$this->attribute});
            }
        } else {
            if ($this->unlinkOnSave === true && !$model->getIsNewRecord() && $model->isAttributeChanged($this->attribute)) {
                $this->delete($this->attribute, true);
            }
        }
    }

    /**
     * This method is called at the end of inserting or updating a record.
     *
     * @throws \yii\base\InvalidArgumentException
     * @throws \yii\base\Exception
     */
    public function afterSave(): void
    {
        if (
            $this->_file instanceof UploadedFile ||
            $this->_file instanceof UploadedRemoteFile
        ) {
            $path = $this->getUploadPath($this->attribute);
            if (is_string($path) && FileHelper::createDirectory(dirname($path))) {
                $this->saveFile($this->_file, $path);
                $this->afterUpload();
            } else {
                throw new InvalidArgumentException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
            }
        }
    }

    /**
     * This method is invoked after deleting a record.
     */
    public function afterDelete(): void
    {
        $attribute = $this->attribute;
        if ($this->unlinkOnDelete && $attribute) {
            $this->delete($attribute);
        }
    }

    /**
     * Returns file path for the attribute.
     *
     * @param string  $attribute
     * @param boolean $old
     * @return string|null the file path.
     */
    public function getUploadPath($attribute, $old = false): ?string
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        $path = $this->resolvePath($this->path);
        $fileName = ($old === true) ? $model->getOldAttribute($attribute) : $model->$attribute;

        return $fileName ? Yii::getAlias($path . '/' . $fileName) : null;
    }

    /**
     * Returns file url for the attribute.
     *
     * @param string $attribute
     * @return string|null
     */
    public function getUploadUrl($attribute): ?string
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        $url = $this->resolvePath($this->url);
        $fileName = $model->getOldAttribute($attribute);

        return $fileName ? Yii::getAlias($url . '/' . $fileName) : null;
    }

    /**
     * Returns the UploadedFile instance.
     *
     * @return UploadedFile
     */
    protected function getUploadedFile(): UploadedFile
    {
        return $this->_file;
    }

    /**
     * Replaces all placeholders in path variable with corresponding values.
     *
     * @param $path
     * @return null|string|string[]
     */
    protected function resolvePath($path)
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;

        return preg_replace_callback('/{([^}]+)}/', function ($matches) use ($model) {
            $name = $matches[1];
            $attribute = ArrayHelper::getValue($model, $name);
            if (is_string($attribute) || is_numeric($attribute)) {
                return $attribute;
            }

            return $matches[0];
        }, $path);
    }

    /**
     * Saves the uploaded file.
     *
     * @param UploadedFile|UploadedRemoteFile $file the uploaded file instance
     * @param string                          $path the file path used to save the uploaded file
     * @return boolean true whether the file is saved successfully
     */
    protected function saveFile($file, $path): bool
    {
        if ($file instanceof UploadedRemoteFile) {
            return $file->saveAs($path, true);
        }

        return $file->saveAs($path, $this->deleteTempFile);
    }

    /**
     * Deletes old file.
     *
     * @param string  $attribute
     * @param boolean $old
     */
    protected function delete($attribute, $old = false): void
    {
        $path = $this->getUploadPath($attribute, $old);
        if (is_file($path)) {
            unlink($path);
        }
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function getFileName($file): string
    {
        if ($this->generateNewName) {
            return $this->generateNewName instanceof Closure
                ? call_user_func($this->generateNewName, $file)
                : $this->generateFileName($file);
        }

        return self::sanitize($file->name);
    }

    /**
     * Replaces characters in strings that are illegal/unsafe for filename.
     *
     * #my*  unsaf<e>&file:name?".png
     *
     * @param string $filename the source filename to be "sanitized"
     * @return string string the sanitized filename
     */
    public static function sanitize($filename): string
    {
        return str_replace([' ', '"', '\'', '&', '/', '\\', '?', '#'], '-', $filename);
    }

    /**
     * Generates random filename.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFileName($file): string
    {
        return uniqid('fl', false) . '.' . $file->extension;
    }

    /**
     * This method is invoked after uploading a file.
     * The default implementation raises the [[EVENT_AFTER_UPLOAD]] event.
     * You may override this method to do postprocessing after the file is uploaded.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterUpload(): void
    {
        $this->owner->trigger(self::EVENT_AFTER_UPLOAD);
    }
}
