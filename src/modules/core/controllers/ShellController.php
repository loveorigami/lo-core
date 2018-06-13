<?php

namespace lo\core\modules\core\controllers;

use lo\wshell\actions\ShellAction;
use Yii;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Class CommandsController
 * @package lo\core\modules\core\controllers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ShellController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'migrate' => [
                'class' => ShellAction::class,
                'command' => 'migrate --interactive=0'
            ],
            'clear-dir' => [
                'class' => ShellAction::class,
                'command' => 'dir/del'
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
