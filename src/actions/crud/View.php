<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class View
 * Класс действия просмотра модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class View extends Base
{
    /** @var string путь к шаблону для отображения */
    public $tpl = "view";

    /**
     * Запуск действия просмотра модели
     * @param integer $id идентификатор модели
     * @return string
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if (!$model)
            throw new NotFoundHttpException('Not found');

        if (!Yii::$app->user->can($this->access(), array("model" => $model)))
            throw new ForbiddenHttpException('Forbidden model');

        return $this->render($this->tpl, ["model" => $model]);
    }
}