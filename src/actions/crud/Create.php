<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveRecord;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class Create
 * Класс действия создания элемента модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Create extends Base
{
    /** @var string сценарий для валидации */
    public $modelScenario = ActiveRecord::SCENARIO_INSERT;

    /** @var string путь к шаблону для отображения */
    public $tpl = "create";

    /**
     * Запуск действия
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function run()
    {
        /** @var ActiveRecord $model */
        $model = Yii::createObject(["class" => $this->modelClass, 'scenario' => $this->modelScenario]);

        if (!Yii::$app->user->can($this->access(), array("model" => $model)))
            throw new ForbiddenHttpException('Forbidden');

        $this->checkForbiddenAttrs($model);

        $request = Yii::$app->request;

        $model->attributes = $this->defaultAttrs;

        $load = $model->load($request->post());

        if ($load && $request->post($this->validateParam)) {
            return $this->performAjaxValidation($model);
        }

        if ($load && !Yii::$app->user->can($this->access(), array("model" => $model)))
            throw new ForbiddenHttpException('Forbidden');

        if ($load && $model->save()) {

            $returnUrl = $this->getReturnUrl();

            if (Yii::$app->request->isAjax) {
                // JSON response is expected in case of successful save
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'id' => $model->id
                ];
            }

            if ($request->post($this->applyParam)) {
                return $this->controller->redirect([$this->updateUrl, 'id' => $model->id, $this->redirectParam => $returnUrl]);
            } else {
                return $this->controller->redirect($returnUrl);
            }
        }

        return $this->render($this->tpl, [
            'model' => $model,
        ]);

    }
}