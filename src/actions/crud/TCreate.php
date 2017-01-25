<?php
namespace lo\core\actions\crud;

use lo\core\db\TActiveRecord;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

/**
 * Class TCreate
 * Класс действия создания элемента древовидной модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TCreate extends Create
{
    /** @var array массив атрибутов значения которых должны наследоваться от родительской модели */

    public $extendedAttrs = [];

    /**
     * Запуск действия
     * @param int $parent_id идентификатор родительской модели
     * @return string
     * @throws ForbiddenHttpException
     * @throws BadRequestHttpException
     */
    public function run($parent_id = null)
    {
        /** @var TActiveRecord $class */
        $class = $this->modelClass;

        /** @var TActiveRecord $model */
        $model = Yii::createObject(["class" => $this->modelClass, 'scenario' => $this->modelScenario]);

        $model->parent_id = $parent_id;

        if (!Yii::$app->user->can($this->access(), array("model" => $model))) {
            throw new ForbiddenHttpException('Forbidden');
        }

        $this->checkForbiddenAttrs($model);

        $request = Yii::$app->request;

        $model->attributes = $this->defaultAttrs;

        $load = $model->load($request->post());

        $parentModel = $class::find()->where(["id" => (int)$model->parent_id])->one();

        if ($parentModel AND $parentModel->id != TActiveRecord::ROOT_ID AND !empty($this->extendedAttrs)) {
            foreach ($this->extendedAttrs AS $attr) {
                if (empty($model->attr))
                    $model->$attr = $parentModel->$attr;
            }
        }

        if ($load && $request->post($this->validateParam)) {
            return $this->performAjaxValidation($model);
        }

        if ($load && $model->validate() && $parentModel && $model->appendTo($parentModel)) {

            $returnUrl = $this->getReturnUrl();

            if ($request->post($this->applyParam))
                return $this->controller->redirect([$this->updateUrl, 'id' => $model->id, $this->redirectParam => $returnUrl]);
            else {
                return $this->controller->redirect($returnUrl);
            }

        } else {
            return $this->render($this->tpl, [
                'model' => $model,
            ]);
        }
    }
}