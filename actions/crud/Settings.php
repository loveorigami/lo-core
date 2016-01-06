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
 * @author Churkin Anton <webadmin87@gmail.com>
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
            Yii::$app->session->setFlash('alert', [
                'body' => Yii::t('backend', 'Settings was successfully saved'),
                'options' => ['class' => 'alert alert-success']
            ]);
            return $this->refresh();
        }

        return $this->render($this->tpl, ['model' => $model]);
    }

}