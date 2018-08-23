<?php

namespace lo\core\helpers;

use lo\core\exceptions\FlashForbiddenException;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class RbacHelper
 *
 * @package lo\core\helpers
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class RbacHelper
{
    public const B_CREATE = 'BCreate';
    public const B_UPDATE = 'BUpdate';
    public const B_VIEW = 'BView';
    public const B_DELETE = 'BDelete';

    /** With Rules */
    public const B_DELETE_OWN = 'BDeleteOwn';
    public const B_PERM_OWN = 'ownModelPerm';

    /**
     * @param        $rule
     * @param        $model
     * @param string $attr
     * @return mixed
     */
    public static function canUser($rule, $model, $attr = 'author_id'): bool
    {
        $user = App::user();

        if ($user) {
            return $user->can($rule, [
                'model' => $model,
                'attribute' => $attr,
            ]);
        }

        return false;
    }

    /**
     * @deprecated
     * @param        $model
     * @param string $attr
     * @return mixed
     */
    public static function canDelete($model, $attr = 'author_id')
    {
        return self::canUser(self::B_DELETE, $model, $attr);
    }

    /**
     * @deprecated
     * @param        $model
     * @param string $attr
     * @return mixed
     */
    public static function canUpdate($model, $attr = 'author_id')
    {
        return self::canUser(self::B_UPDATE, $model, $attr);
    }

    /**
     * @param        $rule
     * @param        $model
     * @param string $attr
     * @throws FlashForbiddenException
     * @throws ForbiddenHttpException
     */
    public static function canUserOrForbidden($rule, $model, $attr = 'author_id'): void
    {
        if (!self::canUser($rule, $model, $attr)) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->session->setFlash('error', App::t('Forbidden access {rule} to {model}', [
                    'model' => \get_class($model),
                    'rule' => $rule,
                ]));
                throw new FlashForbiddenException(App::t('Forbidden access {rule} to {model}', [
                    'model' => \get_class($model),
                    'rule' => $rule,
                ]));
            }

            throw new ForbiddenHttpException(App::t('Forbidden access'));
        }
    }

    /**
     * @deprecated
     * @param        $model
     * @param string $attr
     * @throws FlashForbiddenException
     * @throws ForbiddenHttpException
     */
    public static function canDeleteOrForbidden($model, $attr = 'author_id'): void
    {
        if (!self::canUser(self::B_DELETE, $model, $attr)) {
            if (Yii::$app->request->isAjax) {
                throw new FlashForbiddenException(App::t('Forbidden delete model'));
            }

            throw new ForbiddenHttpException(App::t('Forbidden delete model'));
        }
    }

    /**
     * @deprecated
     * @param        $model
     * @param string $attr
     * @throws FlashForbiddenException
     * @throws ForbiddenHttpException
     */
    public static function canUpdateOrForbidden($model, $attr = 'author_id'): void
    {
        if (!self::canUser(self::B_UPDATE, $model, $attr)) {
            if (Yii::$app->request->isAjax) {
                throw new FlashForbiddenException(App::t('Forbidden update model'));
            }

            throw new ForbiddenHttpException(App::t('Forbidden update model'));
        }
    }
}
