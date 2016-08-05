<?php
namespace lo\core\actions\crud;

use lo\core\db\TActiveRecord;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class XEditable
 * Класс действия обновления модели через расширение XEditable
 * @package lo\core\actions\crud
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class XEditable extends \lo\core\actions\Base
{

    /**
     * @var string сценарий валидации
     */

    public $modelScenario = 'update';

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
            $pk = unserialize(base64_decode($pk));

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