<?php
namespace lo\core\rbac;

use lo\core\interfaces\IConstraint;
use Yii;
use yii\base\Object;

/**
 * Class AuthorConstraint
 * Ограничение по автору модели
 * @package lo\core\rbac
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class AuthorConstraint extends Object implements IConstraint
{
    /**
     * Устанавливает ограничение на критерий запроса
     * @param \lo\core\db\ActiveQuery $query запрос
     */
    public function applyConstraint($query)
    {
        $userId = Yii::$app->user->id;
        $query->andWhere([$query->getAlias().".author_id" => $userId]);
    }
}