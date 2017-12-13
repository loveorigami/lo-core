<?php

namespace lo\core\traits;

use Yii;
use yii\base\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\View;

/**
 * Class BaseRouteTrait
 * Получаем роуты для формирования access rules
 * @property View $view
 * @property Controller $context
 * @package lo\core\rbac
 */
trait AccessRouteTrait
{
    /** @var string базовая часть маршрута к действиям */
    protected $_baseRoute;

    /** @var string полный маршрут */
    protected $_permRoute;

    /**
     * Возвращает базовый роут
     * @return string
     */
    public function getBaseRoute()
    {
        if ($this->_baseRoute === null) {
            $context = $this->view->context;
            /** @var Controller $context */
            $this->_baseRoute = "/" . $context->uniqueId;
        }
        return $this->_baseRoute;
    }

    /**
     * Route for permission
     */
    protected function getPermRoute()
    {
        if ($this->_permRoute === null)
            $this->_permRoute = '/' . \Yii::$app->controller->route;
        return $this->_permRoute;
    }

    /**
     * Route for permission
     * @param null $action
     * @return string
     */
    protected function access($action = null)
    {
        if (Yii::$app->user->can('root')) {
            return '/*';
        }

        if ($action) {
            return $this->getBaseRoute() . '/' . $action;
        }

        return $this->getPermRoute();
    }

    /**
     * @param $model
     * @throws ForbiddenHttpException
     */
    public function canAction($model)
    {
        if (!Yii::$app->user->can($this->access(), ["model" => $model])) {
            throw new ForbiddenHttpException('Forbidden model');
        }
    }
    /**
     * @param $description
     * @param $rule
     * @param array $params
     * @return string
     */
    /*
    protected function renderAccess($description, $rule, $params = [])
       {
           $access = Yii::$app->user->can($rule, $params);
           return $description.': '.($access ? 'yes' : 'no')      }

   /*    public function actionTest()
       {
           $post = new stdClass();
           $post->createdBy = User::findByUsername('demo')->id;
           return $this->renderContent(
               Html::tag('h1', 'Current permissions') .
               Html::ul([
                   $this->renderAccess('Use can create post', 'createPost'),
                   $this->renderAccess('Use can read post', 'readPost'),
                   $this->renderAccess('Use can update post', 'updatePost'),
                   $this->renderAccess('Use can own update post', 'updateOwnPost', [
                       'post' => $post,
                   ]),
                   $this->renderAccess('Use can delete post', 'deletePost'),
               ])
           );
       }*/

}