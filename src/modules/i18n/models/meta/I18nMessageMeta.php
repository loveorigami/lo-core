<?php

namespace lo\core\modules\i18n\models\meta;

use lo\core\db\MetaFields;
use lo\core\db\fields;
use lo\core\helpers\ArrayHelper;
use Yii;

/**
 */
class I18nMessageMeta extends MetaFields
{
    /**
     * @return array
     */
    public function getLanguages()
    {
        $owner = $this->owner;
        $data = $owner::find()->asArray()->groupBy('language')->all();
        return ArrayHelper::map($data, 'language', 'language');
    }

    /**
     * @inheritdoc
     */
    protected function config()
    {
        return [
            "language" => [
                "definition" => [
                    "class" => fields\ListField::class,
                    "title" => Yii::t('backend', 'Language'),
                    "data" => [$this, 'getLanguages'],
                    "rules" => [
                        // [['language'], 'unique', 'targetAttribute' => ['id', 'language']]
                        'unique' => ['targetAttribute' => ['id', 'language']],
                    ],
                    "showInGrid" => true,
                    "showInFilter" => true,
                    "isRequired" => true,
                    "editInGrid" => false,
                ],
                "params" => [$this->owner, "language"]
            ],
            
            "id" => [
                'definition' => [
                    "class" => fields\ajax\DropDownField::class,
                    "relationName" => "sourceMessageModel",
                    "loadUrl" => ['/i18n/i18n-source-message/list'],
                    "title" => Yii::t('backend', 'Message'),
                    "gridAttr" => "message",
                ],
                "params" => [$this->owner, "id"],
            ],

            "translation" => [
                "definition" => [
                    "class" => fields\TextField::class,
                    "title" => Yii::t('backend', 'Translation'),
                    "showInGrid" => true,
                    "showInFilter" => true,
                    "isRequired" => true,
                    "editInGrid" => true,
                ],
                "params" => [$this->owner, "translation"]
            ],

        ];
    }
}
