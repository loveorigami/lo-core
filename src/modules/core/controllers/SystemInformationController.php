<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */

namespace lo\core\modules\core\controllers;

use probe\Factory;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;
use Yii;

class SystemInformationController extends Controller
{

    public function actionIndex()
    {
        $provider = Factory::create();
        if ($provider) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($key = Yii::$app->request->get('data')) {
                    switch($key){
                        case 'cpu_usage':
                            return$provider->getCpuUsage();
                            break;
                        case 'memory_usage':
                            return ($provider->getTotalMem() - $provider->getFreeMem()) / $provider->getTotalMem();
                            break;
                    }
                }
            } else {
                return $this->render('index', ['provider' => $provider]);
            }
        } else {
            return $this->render('fail');
        }
    }
}
