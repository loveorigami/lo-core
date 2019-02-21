<?php

namespace lo\core\modules\main\controllers;

use lo\core\modules\main\models\IncludeItem;
use yii\web\Controller;
use lo\core\actions\crud;

/**
 * Class IncludeItemController
 * @package lo\core\modules\main\modules\admin\controllers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class IncludeItemController extends Controller
{
    /**
     * Действия
     * @return array
     */

    public function actions()
    {
        $class = IncludeItem::class;
        return [
            'index' => [
                'class' => crud\Index::class,
                'modelClass' => $class,
                'orderBy' => ['name' => SORT_ASC]
            ],
            'view' => [
                'class' => crud\View::class,
                'modelClass' => $class,
            ],
            'create' => [
                'class' => crud\Create::class,
                'modelClass' => $class,
            ],
            'update' => [
                'class' => crud\Update::class,
                'modelClass' => $class,
            ],

            'delete' => [
                'class' => crud\Delete::class,
                'modelClass' => $class,
            ],

            'editable' => [
                'class' => crud\XEditable::class,
                'modelClass' => $class,
            ],

            'list' => [
                'class' => crud\ListStr::class,
                'modelClass' => $class,
            ],
        ];
    }

}
