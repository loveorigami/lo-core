<?php

namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;
use lo\core\helpers\PkHelper;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class GroupDelete
 * Класс для группового удаления моделей
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TGroupStatus extends Base
{
    /** @var string имя параметра в запросе в котором передаются идентификаторы материалов при групповых операциях */
    public $groupIdsAttr = "selection";

    /** @var string сценарий для валидации */
    public $modelScenario = ActiveRecord::SCENARIO_SEARCH;

    /** @var  int */
    public $status;

    /**
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run()
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;
        $ids = Yii::$app->request->post($this->groupIdsAttr, []);
        $ids = PkHelper::decodeAll($ids);

        if (!empty($ids)) {
            /** @var ActiveQuery $query */
            $query = $class::findByPk($ids);
            foreach ($query->all() as $model) {
                if (!Yii::$app->user->can($this->access(), ["model" => $model])) {
                    throw new ForbiddenHttpException('Forbidden');
                }

                $childIds = $model->getDescendants()->column();
                if ($childIds) {
                    $model::updateAll(['status' => $this->status], ['id' => $childIds]);
                }
            }
        }
        $class::updateAll(['status' => $this->status], ['id' => $ids]);

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

        return null;
    }
}