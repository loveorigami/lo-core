<?php
namespace lo\core\rbac;

/**
 * Interface IConstraint
 * Интерфейс для создания правил ограничений прав доступа
 * @package lo\core\rbac
 * @author Churkin Anton <webadmin87@gmail.com>
 */

interface IConstraint
{

    /**
     * Устанавливает ограничение на критерий запроса
     * @param \lo\core\db\ActiveQuery $query запрос
     * @return mixed
     */

    public function applyConstraint($query);

    /**
     * Проверка возможности создания модели
     * @param \lo\core\db\ActiveRecord $model
     * @return boolean
     */

}