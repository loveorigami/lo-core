<?php
namespace lo\core\actions\crud;

use lo\core\db\ActiveRecord;
use lo\core\helpers\PkHelper;
use Yii;
use yii\web\ForbiddenHttpException;
use lo\core\actions\Base;
use yii\web\Response;

/**
 * Class Update
 * Класс действия обновления модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Update extends Base
{
    /**@var string сценарий валидации */
    public $modelScenario = ActiveRecord::SCENARIO_UPDATE;

    /** @var string путь к шаблону для отображения */
    public $tpl = "update";

    /**
     * Запуск действия
     * @param integer $id идентификатор модели
     * @return string
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run($id)
    {
        $pk = PkHelper::decode($id);

        /** @var ActiveRecord $model */
        $model = $this->findModel($pk);

        if (!Yii::$app->user->can($this->access(), ["model" => $model])) {
            throw new ForbiddenHttpException('Forbidden model');
        }

        $model->setScenario($this->modelScenario);

        $this->checkForbiddenAttrs($model);

        $request = Yii::$app->request;

        $load = $model->load(Yii::$app->request->post());

        if ($load && $request->post($this->validateParam)) {
            return $this->performAjaxValidation($model);
        }

        /** can change author */
        if ($load && !Yii::$app->user->can($this->access(), ["model" => $model])) {
            throw new ForbiddenHttpException('Forbidden load');
        }

        if ($load && $model->save()) {
            if (Yii::$app->request->isAjax) {
                // JSON response is expected in case of successful save
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'id' => $model->id
                ];
            }

            if (!$request->post($this->applyParam)) {
                $returnUrl = $this->getReturnUrl();
                return $this->controller->redirect($returnUrl);
            }

        }

        return $this->render($this->tpl, [
            'model' => $model,
        ]);
    }

}