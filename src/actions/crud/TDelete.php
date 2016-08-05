<?php
namespace lo\core\actions\crud;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class TDelete
 * Класс действия для удаления древовидной модели
 * @package common\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TDelete extends Delete
{

    /**
     * @inheritdoc
     */

    public function run($id)
    {

        if (Yii::$app->request->isPost) {

            $model = $this->findModel($id);

            if (!Yii::$app->user->can($this->access(), array("model" => $model)))
                throw new ForbiddenHttpException('Forbidden');

            $model->deleteWithChildren();

        }

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

    }

}