<?php

namespace lo\core\behaviors\upload;

use Closure;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;

class UploadFile extends Behavior
{
    /**
     * @event Event an event that is triggered after a file is uploaded.
     */
    const EVENT_AFTER_UPLOAD = 'afterUpload';

    /** @var string the attribute which holds the attachment. */
    public $attribute = '';

    /**
     * @var string|callable path or alias to the directory in which to save files
     * or anonymous function returns directory path
     */
    public $storagePath = '';

    /** @var string|callable url to save files */
    public $storageUrl = '';

    /**
     * @var boolean|callable generate a new unique name for the file
     * set true or anonymous function takes the old filename and returns a new name.
     * @see self::generateFileName()
     */
    public $generateNewName = false;

    /** @var boolean If `true` current attribute file will be deleted */
    public $unlinkOnSave = true;

    /** @var boolean If `true` current attribute file will be deleted after model deletion */
    public $unlinkOnDelete = true;

    /** @var UploadedFile the uploaded file instance. */
    public $file;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function init()
    {
        parent::init();
        if ($this->attribute === null) {
            throw new InvalidConfigException('The "attribute" property must be set.');
        }

        if (!$this->storagePath) {
            $this->storagePath = Yii::getAlias($this->storagePath);
        }

        if (!$this->storageUrl) {
            $this->storageUrl = Yii::getAlias($this->storageUrl);
        }
    }

    /**
     * This method is invoked before validation starts.
     */
    public function beforeValidate()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;

        $this->file = UploadedFile::getInstance($model, $this->attribute);

        if ($this->file instanceof UploadedFile) {
            $this->file->name = $this->getFileName($this->file);
        }
    }

    /**
     * This method is called at the beginning of inserting or updating a record.
     */
    public function beforeSave()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        if ($this->file instanceof UploadedFile) {
            if (!$model->getIsNewRecord() && $this->unlinkOnSave === true) {
                $this->delete($this->attribute, true);
            }
            $model->setAttribute($this->attribute, $this->file->name);
        }
    }

    /**
     * This method is called at the end of inserting or updating a record.
     * @throws \yii\base\InvalidParamException
     */
    public function afterSave()
    {
        if ($this->file instanceof UploadedFile) {
            $path = $this->getUploadPath($this->attribute);
            if (!FileHelper::createDirectory(dirname($path))) {
                throw new InvalidParamException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
            }
            $this->file->saveAs($path);
            $this->afterUpload();
        }
    }

    /**
     * This method is invoked before deleting a record.
     */
    public function beforeDelete()
    {
        $attribute = $this->attribute;
        if ($this->unlinkOnDelete && $attribute) {
            $this->delete($attribute);
        }
    }

    /**
     * Returns file path for the attribute.
     *
     * @param string $attribute
     * @param boolean $old
     *
     * @return string the file path.
     */
    public function getUploadPath($attribute, $old = false)
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $path = $this->resolvePath($this->storagePath);

        $fileName = ($old === true) ? $model->getOldAttribute($attribute) : $model->$attribute;
        return $fileName ? Yii::getAlias($path . DIRECTORY_SEPARATOR . $fileName) : null;
    }

    /**
     * Returns file url for the attribute.
     *
     * @param string $attribute
     * @return string|null
     */
    public function getFileUrl($attribute)
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $url = $this->resolvePath($this->storageUrl);
        $fileName = $model->getOldAttribute($attribute);
        return $fileName ? Yii::getAlias($url . '/' . $fileName) : null;
    }

    /**
     * Checked has file in model
     *
     * @param $attribute
     * @return bool
     */
    public function hasFile($attribute)
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $fileName = $model->getOldAttribute($attribute);
        return !empty($fileName);
    }

    /**
     * Replaces all placeholders in path variable with corresponding values.
     * @param $path
     *
     * @return mixed
     */
    protected function resolvePath($path)
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        return preg_replace_callback('/{([^}]+)}/', function ($matches) use ($model) {
            $name = $matches[1];
            $attribute = $model->getAttribute($name);
            if (is_string($attribute) || is_numeric($attribute)) {
                return $attribute;
            } else {
                return $matches[0];
            }
        }, $path);
    }

    /**
     * Deletes old file.
     * @param string $attribute
     * @param boolean $old
     */
    protected function delete($attribute, $old = false)
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
    protected function getFileName($file)
    {
        if ($this->generateNewName) {
            return $this->generateNewName instanceof Closure
                ? call_user_func($this->generateNewName, $file)
                : $this->generateFileName($file);
        } else {
            return $this->sanitize($file->name);
        }
    }

    /**
     * Replaces characters in strings that are illegal/unsafe for filename.
     *
     * #my*  unsaf<e>&file:name?".png
     *
     * @param string $filename the source filename to be "sanitized"
     * @return boolean string the sanitized filename
     */
    public static function sanitize($filename)
    {
        return str_replace([' ', '"', '\'', '&', '/', '\\', '?', '#'], '-', $filename);
    }

    /**
     * Generates random filename.
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFileName($file)
    {
        return uniqid() . '.' . $file->extension;
    }

    /**
     * This method is invoked after uploading a file.
     * The default implementation raises the [[EVENT_AFTER_UPLOAD]] event.
     * You may override this method to do postprocessing after the file is uploaded.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterUpload()
    {
        $this->owner->trigger(self::EVENT_AFTER_UPLOAD);
    }

}