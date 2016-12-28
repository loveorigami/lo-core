<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveRecord;
use lo\core\helpers\PkHelper;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class XEditable
 * Класс действия обновления модели через расширение XEditable
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class XEditable extends Base
{
    /** @var string сценарий валидации */
    public $modelScenario = ActiveRecord::SCENARIO_UPDATE;

    /**
     * Запуск действия
     * @return boolean
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $pk = Yii::$app->request->post('pk');
            $pk = PkHelper::keyDecode($pk);

            /** @var ActiveRecord $model */
            $model = $this->findModel($pk);

            if (!Yii::$app->user->can($this->access(), array("model" => $model)))
                throw new ForbiddenHttpException('Forbidden');

            $model->setScenario($this->modelScenario);
            $model->{$request->post('name')} = $request->post('value');
            return $model->save();
        }

        return false;
    }
}