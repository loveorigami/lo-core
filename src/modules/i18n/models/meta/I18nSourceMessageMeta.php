<?php

namespace lo\core\modules\i18n\models\meta;

use lo\core\db\MetaFields;
use lo\core\db\fields;
use Yii;
use yii\helpers\ArrayHelper;

class I18nSourceMessageMeta extends MetaFields
{
    /**
     * @return array
     */
    public function getCategories()
    {
        $owner = $this->owner;
        $data = $owner::find()->asArray()->groupBy('category')->all();
        return ArrayHelper::map($data, 'category', 'category');
    }

    /**
     * @inheritdoc
     */
    protected function config()
    {
        return [
            "id" => [
                'definition' => [
                    "class" => fields\PkField::class,
                    "title" => "ID",
                ],
                "params" => [$this->owner, "id"],
                "pos" => 1
            ],
            "category" => [
                "definition" => [
                    "class" => fields\ListField::class,
                    "title" => Yii::t('backend', 'Category'),
                    "data" => [$this, 'getCategories'],
                    "showInGrid" => true,
                    "showInFilter" => true,
                    "isRequired" => true,
                    "editInGrid" => false,
                ],
                "params" => [$this->owner, "category"]
            ],
            "message" => [
                "definition" => [
                    "class" => fields\TextField::class,
                    "title" => Yii::t('backend', 'Message'),
                    "showInGrid" => true,
                    "showInFilter" => true,
                    "isRequired" => true,
                    "editInGrid" => true,
                ],
                "params" => [$this->owner, "message"]
            ],

        ];
    }
}
