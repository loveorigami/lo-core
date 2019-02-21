<?php

namespace lo\core\modules\core\controllers;

use lo\core\modules\core\models\Template;
use lo\core\actions\crud;
use yii\web\Controller;

/**
 * PermissionController implements the CRUD actions for Permission model.
 */
class TemplateController extends Controller
{

    /**
     * Действия
     * @return array
     */
    public function actions()
    {

        $class = Template::class;

        return [

            'index' => [
                'class' => crud\Index::class,
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

            'view' => [
                'class' => crud\View::class,
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

        ];

    }

}