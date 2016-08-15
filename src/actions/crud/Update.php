<?php
namespace lo\core\actions\crud;

use Yii;
use yii\web\ForbiddenHttpException;
use lo\core\actions\Base;

/**
 * Class Update
 * Класс действия обновления модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Update extends Base
{
    /**@var string сценарий валидации */
    public $modelScenario = 'update';

    /** @var string имя параметра запроса содержащего признак "применить" */
    public $applyParam = "apply";

    /** @var string имя параметра запроса содержащего url для редиректа в случае успешного обновления */
    public $redirectParam = "returnUrl";

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

        $model = $this->findModel($id);

        if (!Yii::$app->user->can($this->access(), array("model" => $model)))
            throw new ForbiddenHttpException('Forbidden model');

        $model->setScenario($this->modelScenario);

        $this->checkForbiddenAttrs($model);

        $request = Yii::$app->request;

        $load = $model->load(Yii::$app->request->post());

        if ($load && $request->post($this->validateParam)) {
            return $this->performAjaxValidation($model);
        }

        if ($load && !Yii::$app->user->can($this->access(), array("model" => $model)))
            throw new ForbiddenHttpException('Forbidden load');

        if ($load && $model->save() && !$request->post($this->applyParam)) {

            $returnUrl = $request->post($this->redirectParam);

            if (empty($returnUrl))
                $returnUrl = $this->defaultRedirectUrl;

            return $this->controller->redirect($returnUrl);

        } else {
            return $this->render($this->tpl, [
                'model' => $model,
            ]);
        }

    }

}