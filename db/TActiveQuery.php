<?php
namespace lo\core\db;

use creocoder\nestedsets\NestedSetsQueryBehavior;
/**
 * Class TActiveQuery
 * Системный ActiveQuery. Предоставляет системные scopes. Содержит поведения для реализации древовидных структур
 * @package lo\core\db
 */
class TActiveQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => NestedSetsQueryBehavior::class,
            ],
        ];
    }
}
