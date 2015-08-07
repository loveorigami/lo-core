<?php
namespace lo\core\rbac;
use Yii;
/**
 * Class BaseRouteTrait
 * Получаем роуты для формирования access rules
 * @package lo\core\rbac
 */
trait AccessRouteTrait
{

    /**
     * @var string базовая часть маршрута к действиям
     */
    protected  $_baseRoute;

    /**
     * @var string полный маршрут
     */
    protected  $_permRoute;


    /**
     * Возвращает базовый роут
     * @return string
     */

    public function getBaseRoute()
    {

        if($this->_baseRoute === null)
            $this->_baseRoute = "/" . $this->view->context->uniqueId;

        return $this->_baseRoute;
    }

    /**
     * Route for permission
     */
    protected function getPermRoute()
    {
        if($this->_permRoute === null)
            $this->_permRoute = '/'.\Yii::$app->controller->route;
        return $this->_permRoute;
    }

    /**
     * Route for permission
     */
    protected function access($action='')
    {
        if(Yii::$app->user->can('root')){
            return '/*';
        }

        if($action){
            return $this->baseRoute . '/'. $action;
        }

        return $this->permRoute;
    }

}