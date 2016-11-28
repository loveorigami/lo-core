<?php

namespace lo\core\modules\i18n\models;

use lo\core\db\ActiveRecord;
use lo\core\modules\i18n\models\meta\I18nMessageMeta;

/**
 * This is the model class for table "{{%i18n_message}}".
 *
 * @property integer $id
 * @property string $language
 * @property string $translation
 * @property string $sourceMessage
 * @property string $category
 *
 * @property I18nSourceMessage $sourceMessageModel
 */
class I18nMessage extends ActiveRecord
{
    public $category;
    public $sourceMessage;
    public $useDefaultConfig = false;

    /**
     * @return array
     */
    public static function primaryKey()
    {
        return ['id', 'language'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%i18n_message}}';
    }

    /**
     * @return mixed
     */
    public function metaClass()
    {
        return I18nMessageMeta::class;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessageModel()
    {
        return $this->hasOne(I18nSourceMessage::class, ['id' => 'id']);
    }
}
