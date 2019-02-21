<?php

namespace lo\core\modules\main\controllers;

use lo\core\modules\main\models\IncludeItem;
use Yii;
use lo\core\modules\main\models\IncludeGroup;
use yii\web\Controller;
use lo\core\actions\crud;
use yii\web\Response;

/**
 * PageController implements the CRUD actions for Author model.
 */
class IncludeGroupController extends Controller
{
    /**
     * Действия
     * @return array
     */

    public function actions()
    {
        $class = IncludeGroup::class;
        return [
            'index'=>[
                'class'=> crud\Index::class,
                'modelClass'=>$class,
            ],
            'view'=>[
                'class'=> crud\View::class,
                'modelClass'=>$class,
            ],
            'create'=>[
                'class'=> crud\Create::class,
                'modelClass'=>$class,
            ],
            'update'=>[
                'class'=> crud\Update::class,
                'modelClass'=>$class,
            ],

            'delete'=>[
                'class'=> crud\Delete::class,
                'modelClass'=>$class,
            ],

            'editable' => [
                'class' => crud\XEditable::class,
                'modelClass' => $class,
            ],
        ];
    }


    public function actionList($query)
    {
        $models = IncludeItem::find()->all();
        $items = [];

        foreach ($models as $model) {
            $items[] = ['name' => $model->name];
        }
        // We know we can use ContentNegotiator filter
        // this way is easier to show you here :)
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $items;
    }


}
