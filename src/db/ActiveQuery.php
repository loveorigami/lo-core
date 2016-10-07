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
        $this->andWhere([$this->getAlias().".status" => $state]);
        return $this;
    }

    /**
     * Устанавливает ограничение по признаку на главной
     * @param bool $state если true выбираем активные иначе не активные
     * @return $this
     */
    public function onmain($state = true)
    {
        $this->andWhere([$this->getAlias().".on_main" => $state]);
        return $this;
    }

    /**
     * @param $slug
     * @return $this
     */
    public function bySlug($slug)
    {
        $this->andWhere([$this->getAlias().".slug" => $slug]);
        return $this;
    }

    /**
     * Получение alias-a для таблицы
     * @return null|string
     */
    protected function getAlias()
    {
        $alias = null;

        if (empty($this->from)) {
            /* @var $modelClass ActiveRecord */
            $modelClass = $this->modelClass;
            $alias = $modelClass::tableName();
        }

        return $alias;
    }
}
