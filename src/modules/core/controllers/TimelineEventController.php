<?php

namespace lo\core\modules\core\controllers;

use lo\core\modules\core\models\search\TimelineEventSearch;
use Yii;
use yii\web\Controller;

/**
 * PermissionController implements the CRUD actions for Permission model.
 */
class TimelineEventController extends Controller
{
    /**
     * Lists all TimelineEvent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TimelineEventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => ['created_at' => SORT_DESC]
        ];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}