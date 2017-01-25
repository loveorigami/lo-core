<?php
namespace lo\core\traits;

use lo\core\db\ActiveRecord;
use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class ActionTrait
 * Базовый трейт для CRUD действий
 * @package lo\core\traits
 */
trait ActionTrait
{
    /** @var string имя класса модели */
    public $modelClass;

    /** @var string путь к шаблону для отображения */
    public $view;

    /** @var string название параметра запроса, который служит признаком ajax валидации */
    public $validateParam = "ajax";

    /** @var array атрибуты по умолчанию */
    public $defaultAttrs = [];

    /** @var array массив дополнительных параметров передаваемых в представление */
    public $viewParams = [];

    /** @var string url для редиректа по умолчанию, используется в отсутствие $redirectParam в запросе */
    public $defaultRedirectUrl = ["/"];

    /** @var string имя параметра запроса содержащего признак "применить" */
    public $applyParam = "apply";

    /** @var string имя параметра запроса содержащего url для редиректа в случае успешного обновления */
    public $redirectParam = "returnUrl";

    /** @var string адрес для редиректа в случае нажатия кнопки применить */
    public $updateUrl = "update";

    /**
     * Ajax валидация модели
     * @param \yii\db\ActiveRecord|array $model
     * @return array
     */
    protected function performAjaxValidation($model)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (is_array($model)) {
            return call_user_func_array([ActiveForm::class, 'validate'], $model);
        } else {
            return ActiveForm::validate($model);
        }
    }

    /**
     * @param integer $id
     * @return Model $model
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;
        if (($model = $class::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Проверяет попытку изменения запрещенных атрибутов
     * @param \lo\core\db\ActiveRecord $model
     * @throws \yii\web\ForbiddenHttpException
     */
    protected function checkForbiddenAttrs($model)
    {
        $attrs = Yii::$app->request->post($model->formName(), []);
        $perm = $model->getPermission();

        if ($perm AND $perm->hasForbiddenAttrs($attrs)) {
            throw new ForbiddenHttpException('Forbidden attributes');
        }
    }

    /**
     * Возвращает url после action
     * @return array|mixed|string
     */
    protected function getReturnUrl()
    {
        $request = Yii::$app->request;
        $returnUrl = $request->post($this->redirectParam);
        if (empty($returnUrl)) {
            $returnUrl = $this->defaultRedirectUrl;
        }
        return $returnUrl;
    }
}