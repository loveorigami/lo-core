<?php
namespace lo\core\actions\crud;

use lo\core\db\TActiveRecord;
use lo\core\helpers\PkHelper;
use Yii;
use yii\web\ForbiddenHttpException;

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

        if (!Yii::$app->user->can($this->access(), array("model" => $model)))
            throw new ForbiddenHttpException('Forbidden');

        $model->setScenario($this->modelScenario);

        $this->checkForbiddenAttrs($model);

        $request = Yii::$app->request;

        $parentModel = $model->getParents(1)->one();

        $model->parent_id = $parentModel->id;

        $load = $model->load(Yii::$app->request->post());

        if ($load && $request->post($this->validateParam)) {
            return $this->performAjaxValidation($model);
        }

		if ($parentModel->id != (int) $model->parent_id) {
			$parentModel = $class::find()->where(["id" => (int) $model->parent_id])->one();
		} else {
			$parentModel = null;
		}

        if ($load && $parentModel)
            $res = $model->prependTo($parentModel)->save();
        elseif ($load)
            $res = $model->save();

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