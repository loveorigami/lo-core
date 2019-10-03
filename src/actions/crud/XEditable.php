<?php

namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveRecord;
use lo\core\db\fields\OptimisticLocksField;
use lo\core\helpers\PkHelper;
use lo\core\helpers\RbacHelper;
use Yii;

/**
 * Class XEditable
 * Класс действия обновления модели через расширение XEditable
 *
 * @package lo\core\actions\crud
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class XEditable extends Base
{
    /** @var string сценарий валидации */
    public $modelScenario = ActiveRecord::SCENARIO_UPDATE;

    /** @var string */
    protected $basePermission = RbacHelper::B_UPDATE;

    /** @var string */
    public $userPermission;

    /**
     * Запуск действия
     *
     * @return boolean
     * @throws \lo\core\exceptions\FlashForbiddenException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run(): bool
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $pk = Yii::$app->request->post('pk');
            $pk = PkHelper::keyDecode($pk);

            /** @var ActiveRecord $model */
            $model = $this->findModel($pk);
            $model->setScenario($this->modelScenario);
            $this->getPermissionOrForbidden($model);

            $model->{$request->post('name')} = $request->post('value');

            if ($model->hasMethod('optimisticLock')) {
                $model->detachBehavior(OptimisticLocksField::BEHAVIOR_NAME);
            }

            if ($model->save(true, [$request->post('name')])) {
                Yii::$app->session->setFlash('success', Yii::t('core', 'Saved'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('core', 'Not saved'));
            }

            return true;
        }

        return false;
    }
}
