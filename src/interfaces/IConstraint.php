<?php
namespace lo\core\interfaces;
use yii\db\ActiveQuery;

/**
 * Interface IConstraint
 * Интерфейс для создания правил ограничений прав доступа
 * @package lo\core\rbac
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
interface IConstraint
{
    /**
     * Устанавливает ограничение на критерий запроса
     * @param ActiveQuery $query запрос
     */
    public function applyConstraint($query);

}