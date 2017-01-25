<?php

namespace lo\core\helpers;

use common\modules\user\models\Profile;
use common\modules\user\models\User;
use Yii;
use yii\web\Controller;
use yii\web\View;

/**
 * Class UserHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class UserHelper
{
    const ROLE_ROOT = 'root';
    const ROLE_ADMIN = 'admin';
    const ROLE_EDITOR = 'editor';
    const ROLE_AUTHOR = 'author';

    /**
     * Default role in
     * ```
     *  'authManager' => [
     *      'class' => 'yii\rbac\DbManager',
     *      'defaultRoles' => ['guest'],
     *  ],
     * ```
     */
    const ROLE_GUEST = 'guest';

    /**
     * @return bool
     */
    public static function isGuest()
    {
        return Yii::$app->user->isGuest;
    }

    /**
     * @param $permission
     * @return bool
     */
    public static function can($permission)
    {
        return Yii::$app->user->can($permission);
    }

    /**
     * @param $attr
     * @return mixed|null
     */
    public static function profile($attr)
    {
        if (self::isGuest()) return null;
        /** @var Profile $profile */
        $profile = Yii::$app->user->identity->profile;
        return ArrayHelper::getValue($profile, $attr);
    }

    /**
     * @param $attr
     * @return mixed|null
     */
    public static function user($attr)
    {
        if (self::isGuest()) return null;
        /** @var User $profile */
        $user = Yii::$app->user->identity;
        return ArrayHelper::getValue($user, $attr);
    }

    /**
     * @param $view
     * @param Controller|View $context
     * @return string
     */
    public static function view($view, $context = null)
    {
        if (self::isGuest()) return $view;

        if (self::can(self::ROLE_ADMIN) && ViewHelper::exist($view . '_' . self::ROLE_ADMIN, $context)) {
            return $view . '_' . self::ROLE_ADMIN;
        }

        return $view;
    }

    /**
     * @return \yii\rbac\Role[]
     */
    public static function getRolesByUser()
    {
        return Yii::$app->authManager->getRolesByUser(self::user('id'));
    }
}