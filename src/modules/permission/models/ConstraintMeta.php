<?php

namespace lo\core\modules\permission\models;

use lo\core\db\fields;
use lo\core\db\MetaFields;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ConstraintMeta
 * Мета описание модели прав доступа
 * @package lo\modules\core\models
 */
class ConstraintMeta extends MetaFields
{
    /**
     * @return array
     */
    protected function config()
    {
        return [
            "role" => [
                "definition" => [
                    "class" => fields\ListField::class,
                    "title" => Yii::t('common', 'Role'),
                    "isRequired" => true,
                    "data" => function () {
                        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
                    },
                    "editInGrid" => true,
                ],
                "params" => [$this->owner, "role"]
            ],

            "model" => [
                "definition" => [
                    "class" => fields\TextField::class,
                    "title" => Yii::t('core', 'Model class'),
                    "isRequired" => true,
                    "editInGrid" => true,
                ],
                "params" => [$this->owner, "model"]
            ],

            "constraint" => [
                "definition" => [
                    "class" => fields\TextField::class,
                    "title" => Yii::t('core', 'Constraint class'),
                    "editInGrid" => true,
                ],
                "params" => [$this->owner, "constraint"]
            ],

            "forbidden_attrs" => [
                "definition" => [
                    "class" => fields\TextAreaField::class,
                    "title" => Yii::t('core', 'Forbidden attributes'),
                    "editInGrid" => true,
                ],
                "params" => [$this->owner, "forbidden_attrs"]
            ],
        ];
    }

}