<?php
namespace lo\core\actions\crud;

use common\modules\base\models\ObjectContent;
use lo\core\models\MultiModel;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class MUpdate
 * Класс действия обновления связанных multi моделей
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class MUpdate extends Update
{
    /**
     * @var array
     * $model = new MultiModel([
     *      'models' => [
     *          'account' => $accountModel,
     *          'profile' => $profileModel
     *      ]
     * ])
     * $model->load($_POST);
     * $model->save();
     */
    public $modelRelations = [];

    /**
     * Запуск действия
     * @param integer $id идентификатор модели
     * @return string
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        $content = ObjectContent::findOne(['object_id'=>$model->id]);

        $relations = new MultiModel([
            'models' =>[
                'ObjectItem' => $model,
                'ObjectContent' => $content
            ]
        ]);

        if (!Yii::$app->user->can($this->access(), array("model" => $model)))
            throw new ForbiddenHttpException('Forbidden model');

        $model->setScenario($this->modelScenario);
        $content->setScenario($this->modelScenario);

        $this->checkForbiddenAttrs($model);

        $request = Yii::$app->request;

        $load = $relations->load(Yii::$app->request->post());

        if ($load && $request->post($this->validateParam)) {
            return $this->performAjaxValidation($relations);
        }

        if ($load && !Yii::$app->user->can($this->access(), array("model" => $model)))
            throw new ForbiddenHttpException('Forbidden load');

        if ($load && $relations->save() && !$request->post($this->applyParam)) {

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

    /**
     * Ajax валидация модели
     * @param MultiModel $relations
     * @return array
     */
    protected function performAjaxValidation($relations)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $relations->validate();
        return $relations->validateForm();
    }
}