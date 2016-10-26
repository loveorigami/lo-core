<?php
namespace lo\core\modules\settings\actions;

use lo\core\actions\Base;
use lo\core\modules\settings\models\FormModel;
use Yii;

/**
 * Class Settings
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Settings extends Base
{
    /**
     * @var string путь к шаблону для отображения
     */
    public $tpl = '@lo/core/modules/settings/actions/views/settings';
    public $keys = [];

    /**
     * Запуск действия просмотра модели
     * @return string
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run()
    {
        $model = new FormModel([
            'keys' => $this->keys
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', Yii::t('backend', 'Settings was successfully saved'));
        }

        return $this->render($this->tpl, ['model' => $model]);
    }

}