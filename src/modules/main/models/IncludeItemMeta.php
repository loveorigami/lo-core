<?php

namespace lo\core\modules\main\models;

use lo\core\db\fields;
use lo\core\db\MetaFields;
use lo\core\inputs;
use Yii;

/**
 * Class IncludesMeta
 * Мета описание модели включаемой области
 */
class IncludeItemMeta extends MetaFields
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

            "code" => [
                "definition" => [
                    "class" => fields\SlugField::class,
                    "title" => Yii::t('backend', 'Code'),
                    "isRequired" => true,
                ],
                "params" => [$this->owner, "code"]
            ],

            "text" => [
                "definition" => [
                    "class" => fields\HtmlField::class,
                    "inputClass" => inputs\AceEditorInput::class,
                    "title" => Yii::t('backend', 'Text'),
                    "isRequired" => false,
                    "showInExtendedFilter" => true,
                ],
                "params" => [$this->owner, "text"]
            ],

            "file" => [
                "definition" => [
                    "class" => fields\TextField::class,
                    "title" => Yii::t('backend', 'File path'),
                    "isRequired" => false,
                ],
                "params" => [$this->owner, "file"]
            ],

        ];
    }

}