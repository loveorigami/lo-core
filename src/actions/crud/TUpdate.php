<?php

namespace lo\core\actions\crud;

use lo\core\db\tree\TActiveRecord;
use lo\core\helpers\PkHelper;
use Yii;

/**
 * Class TUpdate
 * Класс действия обновления древовидных моделей
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TUpdate extends Update
{
    /**
     * @inheritdoc
     */
    public function run($id)
    {
        /** @var TActiveRecord $class */
        $class = $this->modelClass;

        $pk = PkHelper::decode($id);
        /** @var TActiveRecord $model */
        $model = $this->findModel($pk);

        $this->canAction($model);

        $model->setScenario($this->modelScenario);

        $this->checkForbiddenAttrs($model);

        $request = Yii::$app->request;

        $parentModel = $model->getParents(1)->one();

        if ($parentModel) {
            $model->parent_id = $parentModel->id;
        }

        $load = $model->load(Yii::$app->request->post());

        if ($load && $request->post($this->validateParam)) {
            return $this->performAjaxValidation($model);
        }

        if ($parentModel && $parentModel->id != (int)$model->parent_id) {
            $parentModel = $class::find()->where(["id" => (int)$model->parent_id])->one();
        } else {
            $parentModel = null;
        }

        if ($load && $parentModel) {
            $res = $model->prependTo($parentModel)->save();
        } elseif ($load) {
            $res = $model->save();
        }

        if (!empty($res) && !$request->post($this->applyParam)) {
            $returnUrl = $this->getReturnUrl();
            return $this->controller->redirect($returnUrl);
        } else {
            return $this->render($this->tpl, [
                'model' => $model,
            ]);
        }
    }
}