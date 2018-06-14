<?php

namespace lo\core\helpers;

use lo\core\db\ActiveRecord;
use lo\core\exceptions\FlashForbiddenException;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class RbacHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class RbacHelper
{
    const B_CREATE = 'BCreate';
    const B_UPDATE = 'BUpdate';
    const B_VIEW = 'BView';
    const B_DELETE = 'BDelete';

    /** With Rules */
    const B_DELETE_OWN = 'BDeleteOwn';
    const B_PERM_OWN = 'ownModelPerm';

    /**
     * @param $rule
     * @param $model
     * @return mixed
     */
    public static function canUser($rule, $model)
    {
        return App::user()->can($rule, ["model" => $model]);
    }

    /**
     * @param $model
     * @return mixed
     */
    public static function canDelete($model)
    {
        return self::canUser(self::B_DELETE, $model);
    }

    /**
     * @param $model
     * @return mixed
     */
    public static function canUpdate($model)
    {
        return self::canUser(self::B_UPDATE, $model);
    }

    /**
     * @param $rule
     * @param ActiveRecord $model
     * @throws FlashForbiddenException
     * @throws ForbiddenHttpException
     */
    public static function canUserOrForbidden($rule, $model)
    {
        if (!self::canUser($rule, $model)) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->session->setFlash('error', App::t('Forbidden access {rule} to {model}', [
                    'model' => get_class($model),
                    'rule' => $rule,
                ]));
                throw new FlashForbiddenException(App::t('Forbidden access {rule} to {model}', [
                    'model' => get_class($model),
                    'rule' => $rule,
                ]));
            } else {
                throw new ForbiddenHttpException(App::t('Forbidden access'));
            }
        };
    }

    /**
     * @param $model
     * @throws FlashForbiddenException
     * @throws ForbiddenHttpException
     */
    public static function canDeleteOrForbidden($model)
    {
        if (!self::canUser(self::B_DELETE, $model)) {
            if (Yii::$app->request->isAjax) {
                throw new FlashForbiddenException(App::t('Forbidden delete model'));
            } else {
                throw new ForbiddenHttpException(App::t('Forbidden delete model'));
            }
        };
    }

    /**
     * @param $model
     * @throws FlashForbiddenException
     * @throws ForbiddenHttpException
     */
    public static function canUpdateOrForbidden($model)
    {
        if (!self::canUser(self::B_UPDATE, $model)) {
            if (Yii::$app->request->isAjax) {
                throw new FlashForbiddenException(App::t('Forbidden update model'));
            } else {
                throw new ForbiddenHttpException(App::t('Forbidden update model'));
            }
        };
    }
}