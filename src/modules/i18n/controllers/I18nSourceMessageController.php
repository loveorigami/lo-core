<?php

namespace lo\core\modules\i18n\controllers;

use lo\core\modules\i18n\models\I18nSourceMessage;
use yii\web\Controller;
use lo\core\actions\crud;

/**
 * I18nSourceMessageController implements the CRUD actions for I18nSourceMessage model.
 */
class I18nSourceMessageController extends Controller
{
    public function actions()
    {
        $class = I18nSourceMessage::class;
        return [
            'index' => [
                'class' => crud\Index::class,
                'modelClass' => $class,
                'orderBy' => ['id' => SORT_DESC]
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
            'groupdelete' => [
                'class' => crud\GroupDelete::class,
                'modelClass' => $class,
            ],
            'editable' => [
                'class' => crud\XEditable::class,
                'modelClass' => $class,
            ],
            'list' => [
                'class' => crud\ListId::class,
                'defaultAttr' => 'message',
                'modelClass' => $class,
            ],
        ];
    }
}
