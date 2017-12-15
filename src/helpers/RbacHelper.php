<?php

namespace lo\core\helpers;

use Yii;
use yii\web\ForbiddenHttpException;


/**
 * Class RbacHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class RbacHelper
{
    const B_DELETE = 'BDelete';
    const B_UPDATE = 'BUpdate';
    const B_VIEW = 'BView';

    public static function can($rule, $model)
    {
        if (!Yii::$app->user->can($rule, ["model" => $model])) {
            throw new ForbiddenHttpException('Forbidden model');
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