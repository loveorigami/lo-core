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
            $model->delete();
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