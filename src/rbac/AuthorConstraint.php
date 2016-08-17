<?php
namespace lo\core\rbac;

use lo\core\db\ActiveRecord;
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
     * @return mixed
     */
    public function applyConstraint($query)
    {
        $userId = Yii::$app->user->id;
        /** @var ActiveRecord $cls */
        $cls = $query->modelClass;
        $table = $cls::tableName();
		
        $query->andWhere([$table.".author_id" => $userId]);

    }
}