<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\helpers\PkHelper;
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
        $pk = PkHelper::decode($id);
        $model = $this->findModel($pk);

        if (!$model){
            throw new NotFoundHttpException('Not found');
        }

        $this->canAction($model);

        return $this->render($this->tpl, ["model" => $model]);
    }
}