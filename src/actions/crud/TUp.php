<?php

namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\TActiveRecord;
use lo\core\helpers\PkHelper;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class TUp
 * Класс действия для перемещения вверх древовидной модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TUp extends Base
{
    /**
     * @param $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function run($id)
    {
        $pk = PkHelper::decode($id);
        /** @var TActiveRecord $model */
        $model = $this->findModel($pk);

        if (!Yii::$app->user->can($this->access('update'), array("model" => $model)))
            throw new ForbiddenHttpException('Forbidden');

        $prevModel = $model->getPrev()->one();

        if ($prevModel){
            $model->insertBefore($prevModel)->save();
        }

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

        return null;
    }
}