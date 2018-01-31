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
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UpdateByParent
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class UpdateByParent extends Base
{
    /**@var string сценарий валидации */
    public $modelScenario = ActiveRecord::SCENARIO_UPDATE;

    /** @var string путь к шаблону для отображения */
    public $tpl = "update";

    /**
     * @var string
     */
    protected $basePermission = RbacHelper::B_UPDATE;

    /** @var string */
    public $userPermission;

    /** @var string */
    public $parentModelClass;

    /** @var string */
    public $childRelation;

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function run($id)
    {
        $pk = PkHelper::decode($id);

        /** @var ActiveRecord $class */
        $class = $this->modelClass;
        $childRelation = $this->childRelation;

        $parentModel = $this->findParentModel($pk);
        $model = $class::findOne($id);

        if ($model == null) {
            $model = \Yii::createObject($class::className());
            $model->link($childRelation, $parentModel);
        }

        try {
            $this->getPermissionOrForbidden($model);
            $this->checkForbiddenAttrs($model);

            $request = Yii::$app->request;
            $load = $model->load(Yii::$app->request->post());

            if ($load && $request->post($this->validateParam)) {
                return $this->performAjaxValidation($model);
            }

            if ($load && $model->save()) {

                Yii::$app->session->setFlash(self::FLASH_SUCCESS, App::t('Item {id} successfully updated', [
                    'id' => $model->getPrimaryKey()
                ]));

                if (Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'success' => true,
                        'id' => $model->id
                    ];
                }

                if (!$request->post($this->applyParam)) {
                    $returnUrl = $this->getReturnUrl();
                    return $this->controller->redirect($returnUrl);
                }
            }

        } catch (FlashForbiddenException $e) {
            $e->catchFlash();
        } catch (ForbiddenHttpException $e) {
            $e->getMessage();
        }

        return $this->render($this->tpl, [
            'model' => $model,
        ]);

    }

    /**
     * @param $id
     * @return null|ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findParentModel($id)
    {
        /** @var ActiveRecord $class */
        $class = $this->parentModelClass;
        if (($model = $class::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}