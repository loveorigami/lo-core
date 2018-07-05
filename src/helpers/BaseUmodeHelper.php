<?php

namespace lo\core\helpers;

use Yii;
use yii\web\Controller;
use yii\web\View;

/**
 * Class UserHelper
 *
 * @package lo\core\helpers
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class BaseUmodeHelper
{
    const ROLE_USER = 'user';
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
     * @param                 $view
     * @param Controller|View $context
     * @return string
     */
    public static function view($view, $context = null)
    {
        if (self::isGuest()) {
            return $view;
        }

        if (self::can(self::ROLE_ADMIN) && ViewHelper::exist($view . '_' . self::ROLE_ADMIN, $context)) {
            return $view . '_' . self::ROLE_ADMIN;
        }

        return $view;
    }

    /**
     * @param integer $id
     * @return \yii\rbac\Role[]
     */
    public static function getRolesByUser($id)
    {
        return Yii::$app->authManager->getRolesByUser($id);
    }

    /**
     * @param $role
     * @return array
     */
    public static function getUserIdsByRole($role)
    {
        return Yii::$app->authManager->getUserIdsByRole($role);
    }

    /**
     * @return array
     */
    public static function getRolesList(): array
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
    }


    /**
     * @return string default role
     */
    public static function getAuthRole()
    {
        $user = Yii::$app->user->identity;
        $id = ArrayHelper::getValue($user, 'id');
        $roles = Memoize::call([self::class, 'getRolesByUser'], [$id]);

        return ArrayHelper::getValue(end($roles), 'name');
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getRoleByUserId($id)
    {
        $roles = self::getRolesByUser($id);

        return ArrayHelper::getValue(end($roles), 'name');
    }
}
