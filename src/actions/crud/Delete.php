<?php
namespace lo\core\actions\crud;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class Delete
 * Класс действия для удаления модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Delete extends \lo\core\actions\Base
{

    /**
     * Запуск действия удаления модели
     * @param integer $id идентификатор модели
     * @throws \yii\web\ForbiddenHttpException
     * @return void|\yii\web\Response
     */

    public function run($id)
    {

        if (Yii::$app->request->isPost) {

            $model = $this->findModel($id);

            if (!Yii::$app->user->can($this->access(), array("model" => $model)))
                throw new ForbiddenHttpException('Forbidden');

            $model->delete();

        }

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

    }

    /**
     * @inheritdoc
     */
    protected function goBack()
    {

        $returnUrl = Yii::$app->request->referrer;

        if (empty($returnUrl))
            $returnUrl = $this->defaultRedirectUrl;

        if(preg_match('!/view/\d+/?$!i',$returnUrl)) {

            $returnUrl = preg_replace('!(view/\d+)!i', 'index', $returnUrl);

        }

        return $this->controller->redirect($returnUrl);

    }

}