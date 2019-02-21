<?php

namespace lo\core\modules\i18n\controllers;

use lo\core\modules\i18n\models\I18nMessage;
use yii\web\Controller;
use lo\core\actions\crud;

/**
 * I18nMessageController implements the CRUD actions for I18nMessage model.
 */
class I18nMessageController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        $class = I18nMessage::class;
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
        ];
    }
}
