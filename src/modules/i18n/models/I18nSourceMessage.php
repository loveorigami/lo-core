<?php

namespace lo\core\modules\i18n\models;

use lo\core\db\ActiveRecord;
use lo\core\modules\i18n\models\meta\I18nSourceMessageMeta;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%i18n_source_message}}".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 *
 * @property i18nMessage[] $messages
 */
class I18nSourceMessage extends ActiveRecord
{
    public $useDefaultConfig = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%i18n_source_message}}';
    }

    /**
     * @return mixed
     */
    public function metaClass()
    {
        return I18nSourceMessageMeta::class;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getI18nMessages()
    {
        return $this->hasMany(I18nMessage::class, ['id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(I18nMessage::class, ['id' => 'id'])->indexBy('language');
    }

    /**
     * populate messages
     */
    public function initI18nMessages()
    {
        $messages = [];
        foreach (Yii::$app->params['languages'] as $language) {
            if (!isset($this->messages[$language])) {
                $message = new I18nMessage;
                $message->language = $language;
                $messages[$language] = $message;
            } else {
                $messages[$language] = $this->messages[$language];
            }
        }
        $this->populateRelation('messages', $messages);
    }

    /**
     * save messages
     */
    public function saveI18nMessages()
    {
        foreach ($this->messages as $message) {
            $this->link('messages', $message);
            $message->save();
        }
    }
}
