<?php
namespace lo\core\db;

use yii\db\ActiveQuery as YiiQuery;

/**
 * Class ActiveQuery
 * Системный ActiveQuery. Предоставляет системные scopes.
 * @package lo\core\db
 * @author Churkin Anton <webadmin87@gmail.com>
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
		$class = $this->modelClass;
		$table = $class::tableName();
        $this->andWhere(["$table.status" => $state]);

        return $this;
    }

    public function bySlug($slug)
    {
		$class = $this->modelClass;
		$table = $class::tableName();
        $this->andWhere(["$table.slug" => $slug]);

        return $this;
    }



}
