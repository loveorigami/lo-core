<?php

namespace lo\core\behaviors\upload;

use lo\core\interfaces\IUploadFile;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;

class UploadPathFile extends Behavior implements IUploadFile
{
    /**@event Event an event that is triggered after a file is uploaded. */
    const EVENT_AFTER_UPLOAD = 'afterUpload';

    /** @var string the attribute which holds the attachment. */
    public $attribute;

    /** @var array the scenarios in which the behavior will be triggered */
    public $scenarios = [];

    /** @var string the base path or path alias to the directory in which to save files. */
    public $path;

    /** @var string the base URL or path alias for this file */
    public $url;

    /** @var boolean If `true` current attribute file will be deleted after model deletion. */
    public $unlinkOnDelete = true;

    /**
     * @inheritdoc
     */
    public function init()
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
    public function events()
    {
        return [
            /*
                BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
                BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
                BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            */
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * Returns file path for the attribute.
     * @param string $attribute
     * @param boolean $old
     * @return string|null the file path.
     */
    public function getUploadPath($attribute, $old = false)
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        $path = $this->path;

        $fileName = ($old === true) ? $model->getOldAttribute($attribute) : $model->$attribute;
        $fileName = basename($fileName);

        return $fileName ? Yii::getAlias($path . '/' . $fileName) : null;
    }

    /**
     * Returns file url for the attribute.
     * @param string $attribute
     * @return string|null
     */
    public function getUploadUrl($attribute)
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        $url = $this->url;
        $fileName = $model->getOldAttribute($attribute);
        $fileName = basename($fileName);

        return $fileName ? Yii::getAlias($url . '/' . $fileName) : null;
    }

    /**
     * This method is invoked after deleting a record.
     */
    public function afterDelete()
    {
        $attribute = $this->attribute;
        if ($this->unlinkOnDelete && $attribute) {
            $this->delete($attribute);
        }
    }

    /**
     * This method is called at the end of inserting or updating a record.
     */
    public function afterSave()
    {
        $this->afterUpload();
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