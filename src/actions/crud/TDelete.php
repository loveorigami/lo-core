<?php
namespace lo\core\actions\crud;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class TDelete
 * Класс действия для удаления древовидной модели
 * @package common\actions\crud
 * @author Churkin Anton <webadmin87@gmail.com>
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