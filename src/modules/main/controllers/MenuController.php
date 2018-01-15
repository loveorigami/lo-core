<?php

namespace lo\core\modules\main\controllers;

use lo\core\modules\main\models\Menu;
use yii\web\Controller;
use lo\core\actions\crud;

/**
 * Class MenuController
 * @package lo\core\modules\main\modules\admin\controllers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class MenuController extends Controller
{
    /**
     * Действия
     * @return array
     */
    public function actions()
    {
        $class = Menu::class;
        return [
            'index' => [
                'class' => crud\TIndex::class,
                'modelClass' => $class,
            ],
            'view' => [
                'class' => crud\View::class,
                'modelClass' => $class,
            ],
            'create' => [
                'class' => crud\TCreate::class,
                'modelClass' => $class,
            ],
            'update' => [
                'class' => crud\TUpdate::class,
                'modelClass' => $class,
            ],
            'delete' => [
                'class' => crud\TDelete::class,
                'modelClass' => $class,
            ],
            'groupdelete' => [
                'class' => crud\TGroupDelete::class,
                'modelClass' => $class,
            ],
            'up' => [
                'class' => crud\TUp::class,
                'modelClass' => $class,
            ],
            'down' => [
                'class' => crud\TDown::class,
                'modelClass' => $class,
            ],
            'replace' => [
                'class' => crud\TReplace::class,
                'modelClass' => $class,
            ],
            'editable' => [
                'class' => crud\XEditable::class,
                'modelClass' => $class,
            ],
            'moveNode' => [
                'class' => 'voskobovich\tree\manager\actions\MoveNodeAction',
                'modelClass' => $class,
            ],
            'deleteNode' => [
                'class' => 'voskobovich\tree\manager\actions\DeleteNodeAction',
                'modelClass' => $class,
            ],
            'updateNode' => [
                'class' => 'voskobovich\tree\manager\actions\UpdateNodeAction',
                'modelClass' => $class,
            ],
            'createNode' => [
                'class' => 'voskobovich\tree\manager\actions\CreateNodeAction',
                'modelClass' => $class,
            ],
        ];
    }

}
