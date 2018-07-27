<?php

namespace lo\core\modules\main\models;

use Yii;
use lo\core\db\MetaFields;


/**
 * Class MenuMeta
 *
 * @package lo\core\modules\main\models
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class MenuMeta extends MetaFields
{
    /**
     * @return array
     */
    protected function config(): array
    {
        return [

            'parent_id' => [
                'definition' => [
                    'class' => \lo\core\db\fields\ParentListField::class,
                    'title' => Yii::t('backend', 'Parent'),
                    'data' => [$this->owner, 'getListTreeData'],
                ],
                'params' => [$this->owner, 'parent_id'],
            ],

            'name' => [
                'definition' => [
                    'class' => \lo\core\db\fields\TextField::class,
                    'title' => Yii::t('backend', 'Name'),
                    'isRequired' => true,
                    'editInGrid' => true,
                ],
                'params' => [$this->owner, 'name'],
            ],

            'code' => [
                'definition' => [
                    'class' => \lo\core\db\fields\TextField::class,
                    'title' => Yii::t('backend', 'Code'),
                    'isRequired' => false,
                    'showInGrid' => true,
                ],
                'params' => [$this->owner, 'code'],
            ],

            'link' => [
                'definition' => [
                    'class' => \lo\core\db\fields\TextField::class,
                    'title' => Yii::t('backend', 'Link'),
                    'isRequired' => false,
                    'editInGrid' => true,
                ],
                'params' => [$this->owner, 'link'],
            ],

            'class' => [
                'definition' => [
                    'class' => \lo\core\db\fields\TextField::class,
                    'title' => Yii::t('backend', 'Css class'),
                    'isRequired' => false,
                    'showInGrid' => false,
                ],
                'params' => [$this->owner, 'class'],
            ],

            'target' => [
                'definition' => [
                    'class' => \lo\core\db\fields\ListField::class,
                    'title' => Yii::t('backend', 'Target'),
                    'isRequired' => false,
                    'showInGrid' => false,
                    'data' => function () {
                        return $this->owner->targetsList();
                    },
                ],
                'params' => [$this->owner, 'target'],
            ],

        ];

    }
}
