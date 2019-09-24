<?php

namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveRecord;
use lo\core\exceptions\FlashForbiddenException;
use lo\core\helpers\App;
use lo\core\helpers\PkHelper;
use lo\core\helpers\RbacHelper;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\db\StaleObjectException;

/**
 * Class Update
 * Класс действия обновления модели
 *
 * @package lo\core\actions\crud
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class Update extends Base
{
    /**@var string сценарий валидации */
    public $modelScenario = ActiveRecord::SCENARIO_UPDATE;

    /** @var string путь к шаблону для отображения */
    public $tpl = 'update';

    /** @var string */
    protected $basePermission = RbacHelper::B_UPDATE;

    /** @var string */
    public $userPermission;

    /**
     * @var \Closure
     */
    public $ajaxCallback;

    /**
     * @param $id
     * @return array|string|Response
     * @throws \yii\web\NotFoundHttpException
     * @throws FlashForbiddenException
     * @throws ForbiddenHttpException
     */
    public function run($id)
    {
        $pk = PkHelper::decode($id);

        /** @var ActiveRecord $model */
        $model = $this->findModel($pk);
        $model->setScenario($this->modelScenario);
        $this->getPermissionOrForbidden($model);

        try {
            $this->checkForbiddenAttrs($model);

            $request = Yii::$app->request;
            $load = $model->load(Yii::$app->request->post());

            if ($load && $request->post($this->validateParam)) {
                return $this->performAjaxValidation($model);
            }

            if ($load && $model->save()) {

                Yii::$app->session->setFlash(self::FLASH_SUCCESS, App::t('Item {id} successfully updated', [
                    'id' => $this->getPk($model),
                ]));

                if (Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    if ($this->ajaxCallback instanceof \Closure) {
                        return \call_user_func($this->ajaxCallback, $model);
                    }

                    return [
                        'success' => true,
                        'id' => $model->id,
                    ];
                }

                if (!$request->post($this->applyParam)) {
                    $returnUrl = $this->getReturnUrl();

                    return $this->controller->redirect($returnUrl);
                } else {
                    $this->controller->refresh();
                }
            }
        } catch (StaleObjectException $e) {
            $model->addError('id', Yii::t('core', 'Blocked! Copy data and refresh!'));
        } catch (ForbiddenHttpException $e) {
            $e->getMessage();
        }

        return $this->render($this->tpl, [
            'model' => $model,
        ]);

    }

}
