<?php

namespace lo\core\modules\main\models;

use lo\core\modules\core\models\Template;
use Yii;
use yii\helpers\ArrayHelper;
use lo\core\db\MetaFields;
use lo\core\db\ActiveRecord;
use lo\core\db\fields;

/**
 * Class IncludeGroupMeta
 * Мета описание группы включаемых областей
 */
class IncludeGroupMeta extends MetaFields
{
    /**
     * Список включаемых областей
     *
     * @return array
     */
    public function getIncludesList(): array
    {
        $models = IncludeItem::find()->orderBy(['name' => SORT_ASC])->asArray()->all();

        return ArrayHelper::map($models, 'id', 'name');
    }

    /**
     * @inheritdoc
     */
    protected function config(): array
    {
        return [
            'name' => [
                'definition' => [
                    'class' => fields\TextField::class,
                    'title' => Yii::t('backend', 'Name'),
                    'isRequired' => true,
                    'editInGrid' => true,
                ],
                'params' => [$this->owner, 'name'],
            ],

            'code' => [
                'definition' => [
                    'class' => fields\TextField::class,
                    'title' => Yii::t('backend', 'Code'),
                    'isRequired' => true,
                    'editInGrid' => true,
                ],
                'params' => [$this->owner, 'code'],
            ],

            'cond_type' => [
                'definition' => [
                    'class' => fields\ListField::class,
                    'title' => Yii::t('backend', 'Condition type'),
                    'isRequired' => true,
                    'showInGrid' => false,
                    'data' => [Template::class, 'getConds'],
                ],
                'params' => [$this->owner, 'cond_type'],
            ],

            'cond' => [
                'definition' => [
                    'class' => fields\TextField::class,
                    'title' => Yii::t('backend', 'Condition'),
                    'isRequired' => false,
                    'showInGrid' => false,
                ],
                'params' => [$this->owner, 'cond'],
            ],

            'pos' => [
                'definition' => [
                    'class' => fields\NumberField::class,
                    'title' => Yii::t('backend', 'Pos'),
                    'defaultValue' => ActiveRecord::DEFAULT_SORT,
                    'isRequired' => false,
                    'editInGrid' => true,
                ],
                'params' => [$this->owner, 'pos'],
            ],

            'includesIds' => [
                'definition' => [
                    'class' => fields\ManyManySortField::class,
                    'inputClassOptions' => [
                        'loadUrl' => ['include-item/list'],
                    ],
                    'title' => Yii::t('backend', 'Includes'),
                    'data' => [$this, 'getIncludesList'],
                    'showInExtendedFilter' => false,
                    'relationName' => 'includes',
                ],
                'params' => [$this->owner, 'includesIds'],
            ],
        ];
    }
}
