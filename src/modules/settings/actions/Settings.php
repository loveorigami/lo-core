<?php
namespace lo\core\modules\settings\actions;

use lo\core\actions\Base;
use lo\core\modules\settings\models\FormModel;
use Yii;
use yii\web\Response;

/**
 * Class Settings
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Settings extends Base
{
    const TYPE_DROPDOWN = FormModel::TYPE_DROPDOWN;
    const TYPE_TEXTINPUT = FormModel::TYPE_TEXTINPUT;
    const TYPE_TEXTAREA = FormModel::TYPE_TEXTAREA;
    const TYPE_CHECKBOX = FormModel::TYPE_CHECKBOX;
    const TYPE_RADIOLIST = FormModel::TYPE_RADIOLIST;
    const TYPE_CHECKBOXLIST = FormModel::TYPE_CHECKBOXLIST;
    const TYPE_WIDGET = FormModel::TYPE_WIDGET;

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
            Yii::$app->session->setFlash('success', Yii::t('backend', 'Settings was successfully saved'));
            if (Yii::$app->request->isAjax) {
                // JSON response is expected in case of successful save
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => true
                ];
            }
        }

        return $this->render($this->tpl, ['model' => $model]);
    }

}