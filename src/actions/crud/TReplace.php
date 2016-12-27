<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\TActiveRecord;
use Yii;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

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

        $class = $this->modelClass;

        if (Yii::$app->request->isGet) {

            $ids = Yii::$app->request->get($this->groupIdsAttr, array());

            $model = Yii::createObject($class);

            $arr = $model->getListTreeData(TActiveRecord::ROOT_ID, $ids);

            foreach ($arr as $k => $v)
                echo Html::tag('option', $v, ["value" => $k]);

            Yii::$app->end();

        }

        if (Yii::$app->request->isPost) {

            $parent_id = Yii::$app->request->post($this->parentIdAttr);

            $parentModel = $this->findModel($parent_id);

            if (!$parentModel)
                throw new BadRequestHttpException("Bad request");

            $ids = Yii::$app->request->post($this->groupIdsAttr, array());

            if (!empty($ids)) {

                foreach ($ids AS $id) {

                    $model = $class::findOne($id);

                    if(!$model)
                        continue;

                    if (!Yii::$app->user->can($this->access(), array("model" => $model)))
                        throw new ForbiddenHttpException('Forbidden');

                    $model->prependTo($parentModel);

                    $parentModel->refresh();

                }

            }

        }

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

    }

}