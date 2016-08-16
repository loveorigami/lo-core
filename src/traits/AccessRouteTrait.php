<?php
namespace lo\core\traits;

use Yii;
use yii\base\Controller;
use yii\web\View;

/**
 * Class BaseRouteTrait
 * Получаем роуты для формирования access rules
 * @property View $view
 *  @property Controller $context
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
        if ($this->_baseRoute === null){
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

}