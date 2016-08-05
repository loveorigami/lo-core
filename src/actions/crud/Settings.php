<?php
namespace lo\core\actions\crud;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use lo\core\components\settings\FormModel;

/**
 * Class View
 * Класс действия просмотра модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Settings extends \lo\core\actions\Base
{

    /**
     * @var string путь к шаблону для отображения
     */

    public $tpl = "@vendor/loveorigami/lo-core/actions/crud/views/settings";
    public $keys = [];

    /**
     * Запуск действия просмотра модели
     * @param integer $id идентификатор модели
     * @return string
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */

    public function run()
    {

        $model = new FormModel([
            'keys'=>$this->keys
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', Yii::t('backend', 'Settings was successfully saved'));
        }

        return $this->render($this->tpl, ['model' => $model]);
    }

}