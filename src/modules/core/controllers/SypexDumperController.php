<?php

namespace lo\core\modules\core\controllers;

use yii\web\Controller;

class SypexDumperController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
