<?php

namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;
use lo\core\helpers\PkHelper;
use Yii;

/**
 * Class GroupDelete
 * Класс для группового удаления моделей
 *
 * @package lo\core\actions\crud
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class GroupStatus extends Base
{
    /** @var string имя параметра в запросе в котором передаются идентификаторы материалов при групповых операциях */
    public $groupIdsAttr = "selection";

    /** @var string сценарий для валидации */
    public $modelScenario = ActiveRecord::SCENARIO_SEARCH;

    public $attr = 'status';

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
            /** @var ActiveRecord $model */
            foreach ($query->all() as $model) {
                $this->canAction($model);
                $model->{$this->attr} = $this->status;
                $model->save();
            }
        }

        if (!Yii::$app->request->isAjax) {
            return $this->goBack();
        }

        return null;
    }
}
