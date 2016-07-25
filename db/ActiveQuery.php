<?php
namespace lo\core\db;

use yii\db\ActiveQuery as YiiQuery;

/**
 * Class ActiveQuery
 * Системный ActiveQuery. Предоставляет системные scopes.
 *
 * @package lo\core\db
 */
class ActiveQuery extends YiiQuery
{
    /**
     * Устанавливает ограничение по признаку активности
     * @param bool $state если true выбираем активные иначе не активные
     * @return $this
     */
    public function published($state = true)
    {
        /** @var ActiveRecord $class */
		$class = $this->modelClass;
		$table = $class::tableName();
        $this->andWhere(["$table.status" => $state]);

        return $this;
    }

    /**
     * @param $slug
     * @return $this
     */
    public function bySlug($slug)
    {
        /** @var ActiveRecord $class */
		$class = $this->modelClass;
		$table = $class::tableName();
        $this->andWhere(["$table.slug" => $slug]);

        return $this;
    }

}
