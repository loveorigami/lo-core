<?php

namespace lo\core\modules\core\models;

use lo\core\db\ActiveRecord;
use lo\core\db\MetaFields;
use lo\core\db\fields;
use Yii;

/**
 * Class TemplateMeta
 * @package lo\modules\core\models
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TemplateMeta extends MetaFields
{
    /**
     * @inheritdoc
     */
    protected function config()
    {
        return [

            "name" => [
                "definition" => [
                    "class" => fields\TextField::class,
                    "title" => Yii::t('backend', 'Name'),
                    "isRequired" => true,
                ],
                "params" => [$this->owner, "name"]
            ],

            "layout" => [
                "definition" => [
                    "class" => fields\TextField::class,
                    "title" => Yii::t('backend', 'Layout'),
                    "isRequired" => true,
                ],
                "params" => [$this->owner, "layout"]
            ],

            "cond_type" => [
                "definition" => [
                    "class" => fields\ListField::class,
                    "title" => Yii::t('backend', 'Condition type'),
                    "isRequired" => true,
                    "showInGrid" => false,
                    "data" =>[$this->owner, "getConds"],
                ],
                "params" => [$this->owner, "cond_type"]
            ],

            "cond" => [
                "definition" => [
                    "class" => fields\TextField::class,
                    "title" => Yii::t('backend', 'Condition'),
                    "isRequired" => false,
                    "showInGrid" => true,
                ],
                "params" => [$this->owner, "cond"]
            ],

            "pos" => [
                "definition" => [
                    "class" => fields\NumberField::class,
                    "title" => Yii::t('backend', 'Pos'),
                    "defaultValue"=>ActiveRecord::DEFAULT_SORT,
                    "isRequired" => false,
                    "editInGrid" => true,
                ],
                "params" => [$this->owner, "pos"]
            ],

            "text" => [
                "definition" => [
                    "class" => fields\HtmlField::class,
                    "title" => Yii::t('backend', 'Text'),
                    "isRequired" => false,
                ],
                "params" => [$this->owner, "text"]
            ],
        ];
    }

}