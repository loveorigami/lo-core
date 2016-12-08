<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveRecord;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class Delete
 * Класс действия для удаления модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Delete extends Base
{
    public $canDelete = true;
    public $canDeleteError = 'error';

    /**
     * Запуск действия удаления модели
     * @param integer $id идентификатор модели
     * @throws ForbiddenHttpException
     * @return void | Response
     */
    public function run($id)
    {
        /** @var ActiveRecord $model */
        if (Yii::$app->request->isPost) {
            $model = $this->findModel($id);
            if (!Yii::$app->user->can($this->access(), array("model" => $model))) {
                throw new ForbiddenHttpException('Forbidden');
            }

            if ($this->canDelete instanceof \Closure) {
                $canDelete = call_user_func($this->canDelete, $model);
            } else {
                $canDelete = $this->canDelete;
            }

            if ($canDelete) {
                $model->delete();
            } else {
                Yii::$app->session->setFlash('error', $this->canDeleteError);
            }

        }

        if (!Yii::$app->request->isAjax) {
            return $this->goBack();
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function goBack()
    {
        $returnUrl = Yii::$app->request->referrer;

        if (empty($returnUrl)) {
            $returnUrl = $this->defaultRedirectUrl;
        }

        if (preg_match('!/view/\d+/?$!i', $returnUrl)) {
            $returnUrl = preg_replace('!(view/\d+)!i', 'index', $returnUrl);
        }

        return $this->controller->redirect($returnUrl);
    }
}