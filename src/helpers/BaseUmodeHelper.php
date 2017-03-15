<?php

namespace lo\core\helpers;

use Yii;
use yii\web\Controller;
use yii\web\View;

/**
 * Class UserHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class BaseUmodeHelper
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

    protected static $_role;

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
        $user = Yii::$app->user->identity;
        $id =  ArrayHelper::getValue($user, 'id');
        return Yii::$app->authManager->getRolesByUser($id);
    }

    /** @return string default role */
    public static function getRole()
    {
        if (!self::$_role) {
            $roles = self::getRolesByUser();
            self::$_role = key($roles);
        }
        return self::$_role;
    }
}