<?php
namespace lo\core\actions\crud;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class TDown
 * Класс действия для перемещения вниз древовидной модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TDown extends \lo\core\actions\Base
{

    /**
     * @inheritdoc
     */

    public function run($id)
    {

        $model = $this->findModel($id);

        if (!Yii::$app->user->can($this->access('update'), array("model" => $model)))
            throw new ForbiddenHttpException('Forbidden');

        $nextModel = $model->next()->one();

        if ($nextModel)
            $model->insertAfter($nextModel);

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

    }

}