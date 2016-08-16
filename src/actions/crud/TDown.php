<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\TActiveRecord;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class TDown
 * Класс действия для перемещения вниз древовидной модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TDown extends Base
{
    /**
     * @inheritdoc
     */
    public function run($id)
    {
        /** @var TActiveRecord $model */
        $model = $this->findModel($id);

        if (!Yii::$app->user->can($this->access('update'), array("model" => $model)))
            throw new ForbiddenHttpException('Forbidden');

        $nextModel = $model->next()->one();

        if ($nextModel)
            $model->insertAfter($nextModel);

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

        return null;
    }
}