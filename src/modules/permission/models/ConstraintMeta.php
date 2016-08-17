<?php

namespace lo\core\modules\permission\models;

use lo\core\db\MetaFields;
use Yii;

/**
 * Class ConstraintMeta
 * Мета описание модели прав доступа
 * @package lo\modules\core\models
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class ConstraintMeta extends MetaFields
{

    /**
     * @inheritdoc
     */

    protected function config()
    {
        return [
            "role" => [
                "definition" => [
                    "class" => \lo\core\db\fields\ListField::class,
                    "title" => Yii::t('common', 'Role'),
                    "isRequired" => true,
                    "data" => function () {
                        return \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
                    },
                    "editInGrid" => true,
                ],
                "params" => [$this->owner, "role"]
            ],

            "model" => [
                "definition" => [
                    "class" => \lo\core\db\fields\TextField::class,
                    "title" => Yii::t('core', 'Model class'),
                    "isRequired" => true,
                    "editInGrid" => true,
                ],
                "params" => [$this->owner, "model"]
            ],

            "constraint" => [
                "definition" => [
                    "class" => \lo\core\db\fields\TextField::class,
                    "title" => Yii::t('core', 'Constraint class'),
                    "editInGrid" => true,
                ],
                "params" => [$this->owner, "constraint"]
            ],

            "forbidden_attrs" => [
                "definition" => [
                    "class" => \lo\core\db\fields\TextAreaField::class,
                    "title" => Yii::t('core', 'Forbidden attributes'),
                    "editInGrid" => true,
                ],
                "params" => [$this->owner, "forbidden_attrs"]
            ],

        ];
    }

}