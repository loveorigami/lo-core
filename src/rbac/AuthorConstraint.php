<?php
namespace lo\core\rbac;

use lo\core\rbac\IConstraint;
use Yii;
use yii\base\Object;

/**
 * Class AuthorConstraint
 * Ограничение по автору модели
 * @package app\modules\main\rbac
 * @author Churkin Anton <webadmin87@gmail.com>
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
        $cls = $query->modelClass;
        $table = $cls::tableName();
		
        $query->andWhere([$table.".author_id" => $userId]);

    }
}