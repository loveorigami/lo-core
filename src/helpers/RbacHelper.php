<?php

namespace lo\core\helpers;

use lo\core\exceptions\FlashForbiddenException;

/**
 * Class RbacHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class RbacHelper
{
    const B_DELETE = 'BDelete';
    const B_DELETE_MANAGER_URL = 'BDeleteManagerUrl';
    const B_DELETE_MANAGER_OWN = 'BDeleteManagerOwn'; // with Owner Rule
    const B_UPDATE = 'BUpdate';
    const B_VIEW = 'BView';



    /**
     * @param $rule
     * @param $model
     * @throws FlashForbiddenException
     */
    public static function can($rule, $model)
    {
        if (App::user()->can($rule, ["model" => $model])) {
            throw new FlashForbiddenException('Forbidden model');
        }
    }

    /**
     * @param $model
     */
    public static function canDelete($model)
    {
        self::can(self::B_DELETE, $model);
    }
}