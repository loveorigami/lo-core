<?php

namespace lo\core\modules\i18n;

use lo\core\modules\i18n\models\I18nSourceMessage;
use yii\i18n\MissingTranslationEvent;

class Module extends \yii\base\Module
{
    public $menuItems;

    public function init()
    {
        parent::init();
    }

    /**
     * @param MissingTranslationEvent $event
     */
    public static function missingTranslation(MissingTranslationEvent $event)
    {
        $sourceMessage = I18nSourceMessage::find()
            ->where('category = :category and message = binary :message', [
                ':category' => $event->category,
                ':message' => $event->message
            ])
            ->with('messages')
            ->one();

        if (!$sourceMessage && $event->message) {
            $sourceMessage = new I18nSourceMessage;
            $sourceMessage->setAttributes([
                'category' => $event->category,
                'message' => $event->message
            ], false);
            $sourceMessage->save(false);
        }
        $sourceMessage->initI18nMessages();
        $sourceMessage->saveI18nMessages();
    }
}