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

/**
 * Class Delete
 * Класс действия для удаления модели
 *
 * @package lo\core\actions\crud
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class DeleteFile extends Base
{
    public $canDelete = true;
    public $canDeleteError = 'error';

    public $userPermission;

    /**
     * Аттрибут проверки авторства на удаление
     *
     * @var string
     */
    public $userAttribute;

    protected $basePermission = RbacHelper::B_DELETE;

    /**
     * @param $id
     * @param $f
     * @return null|Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id, $f)
    {
        if (Yii::$app->request->isPost) {
            $pk = PkHelper::decode($id);
            /** @var ActiveRecord $model */
            $model = $this->findModel($pk);

            $this->getPermissionOrForbidden($model, $this->userAttribute);

            try {
                if ($this->canDelete instanceof \Closure) {
                    $canDelete = \call_user_func($this->canDelete, $model);
                } else {
                    $canDelete = $this->canDelete;
                }

                if ($canDelete && $model->hasProperty($f)) {
                    $model->$f = null;
                    $model->save(false);
                } else {
                    Yii::$app->session->setFlash(self::FLASH_ERROR, $this->canDeleteError);
                }

                Yii::$app->session->setFlash(self::FLASH_SUCCESS, App::t('File {id} successfully deleted', [
                    'id' => $model->getPrimaryKey(),
                ]));
            } catch (FlashForbiddenException $e) {
                $e->catchFlash();
            } catch (ForbiddenHttpException $e) {
                $e->getMessage();
            }
        }

        if (!Yii::$app->request->isAjax) {
            return $this->goBack();
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function goBack(): Response
    {
        $returnUrl = Yii::$app->request->referrer;

        if (empty($returnUrl)) {
            $returnUrl = $this->defaultRedirectUrl;
        }

        if (preg_match('!/view/\d+/?$!i', $returnUrl)) {
            $returnUrl = preg_replace('!(view/\d+)!i', 'index', $returnUrl);
        }

        return $this->controller->redirect($returnUrl);
    }
}
