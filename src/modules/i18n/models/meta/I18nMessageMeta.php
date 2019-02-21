<?php

namespace lo\core\modules\i18n\models\meta;

use lo\core\db\MetaFields;
use lo\core\db\fields;
use lo\core\helpers\ArrayHelper;
use lo\core\modules\i18n\models\I18nMessage;
use lo\core\modules\i18n\models\I18nSourceMessage;
use Yii;
use yii\db\ActiveQuery;

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
     * @return array
     */
    public function getCategories()
    {
        $data = I18nSourceMessage::find()->asArray()->groupBy('category')->all();
        return ArrayHelper::map($data, 'category', 'category');
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

            "category" => [
                "definition" => [
                    "class" => fields\HasOneField::class,
                    "title" => Yii::t('backend', 'Category'),
                    "data" => [$this, 'getCategories'],
                    "relationName" => "sourceMessageModel",
                    "gridAttr" => "category",
                    "showInGrid" => true,
                    "showInFilter" => true,
                    "showInForm" => false,
                    "editInGrid" => false,
                    "queryModifier" => function ($query) {
                        /** @var I18nMessage $owner */
                        $owner = $this->owner;
                        $category = $owner->category;
                        /** @var ActiveQuery $query */
                        return $query->joinWith([
                            'sourceMessageModel' => function ($query) use ($category) {
                                /** @var ActiveQuery $query */
                                return $query->andFilterWhere([
                                    I18nSourceMessage::tableName() . '.category' => $category
                                ]);
                            }
                        ]);
                    }
                ],
                "params" => [$this->owner, "category"]
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
