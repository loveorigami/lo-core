<?php

namespace lo\core\modules\permission\controllers;

use lo\core\actions\crud;
use lo\core\modules\permission\models\Constraint;
use yii\web\Controller;

/**
 * ConstraintController implements the CRUD actions for Permission model.
 */
class ConstraintController extends Controller
{

    /**
     * Действия
     * @return array
     */
    public function actions()
    {
        $class = Constraint::class;

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