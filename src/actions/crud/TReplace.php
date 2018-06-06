<?php

namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\tree\AActiveRecord;
use lo\core\db\tree\TActiveRecord;
use Yii;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;

/**
 * Class TReplace
 * Класс действия для перемещения древовидных моделей в иерархии
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TReplace extends Base
{
    /**
     * @var string имя параметра в запросе в котором передаются идентификаторы материалов при групповых операциях
     */
    public $groupIdsAttr = "selection";

    /**
     * @var string имя параметра POST запроса в котором передается идентификатор категории в которую необходимо перенести модели
     */
    public $parentIdAttr = "replace_parent_id";

    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var TActiveRecord|AActiveRecord $class */
        $class = $this->modelClass;
        $parentModel = null;

        if (Yii::$app->request->isGet) {
            $ids = Yii::$app->request->get($this->groupIdsAttr, array());
            $model = Yii::createObject($class);
            $arr = $model->getListTreeData(TActiveRecord::ROOT_ID, $ids);
            foreach ($arr as $k => $v) {
                $data[] = Html::tag('option', $v, ["value" => $k]);
            }
            return implode('', $data);
            Yii::$app->end();
        }

        if (Yii::$app->request->isPost) {
            $parent_id = Yii::$app->request->post($this->parentIdAttr);

            if ($parent_id > 0) {
                /** @var TActiveRecord $parentModel */
                $parentModel = $this->findModel($parent_id);

                if (!$parentModel)
                    throw new BadRequestHttpException("Bad request");
            }

            $ids = Yii::$app->request->post($this->groupIdsAttr, array());

            if (!empty($ids)) {
                foreach ($ids AS $id) {
                    $model = $class::findOne($id);

                    if (!$model)
                        continue;

                    $this->canAction($model);

                    if ($parent_id > 0) {
                        $model->prependTo($parentModel)->save();
                        $parentModel->refresh();
                    } else {
                        $model->parent_id = $parent_id;
                        $model->save();
                    }
                }
            }
        }

        if (!Yii::$app->request->isAjax) {
            return $this->goBack();
        }

        return null;
    }
}