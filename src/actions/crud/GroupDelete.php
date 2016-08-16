<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveRecord;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class GroupDelete
 * Класс для группового удаления моделей
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class GroupDelete extends Base
{
    /** @var string имя параметра в запросе в котором передаются идентификаторы материалов при групповых операциях */
    public $groupIdsAttr = "selection";

    /** @var string сценарий для валидации */
    public $modelScenario = ActiveRecord::SCENARIO_SEARCH;

    /**
     * Запуск группового удалнеия моделей
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run()
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;

        $ids = Yii::$app->request->post($this->groupIdsAttr, array());

        if (!empty($ids)) {
            $query = $class::find()->where(['id' => $ids]);
            foreach ($query->all() as $model) {
                if (!Yii::$app->user->can($this->access(), array("model" => $model)))
                    throw new ForbiddenHttpException('Forbidden');
                $model->delete();
            }
        }

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

        return null;
    }
}