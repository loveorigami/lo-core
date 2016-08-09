<?php
namespace lo\core\components;

use yii\web\View as YiiView;

/**
 * Class View
 * Расширяем функционал стандартного представления
 * @package lo\core\components
 */
class View extends YiiView
{

    /**
     * @var array массив хлебных крошек для виджета yii\widgets\Breadcrumbs
     * @link http://www.yiiframework.com/doc-2.0/yii-widgets-breadcrumbs.html
     */

    protected $_breadCrumbs = [];

    /**
     * Устанавливает массив хлебных крошек
     * @param array $breadCrumbs
     */
    public function setBreadCrumbs($breadCrumbs)
    {
        $this->_breadCrumbs = $breadCrumbs;
    }

    /**
     * Получает массив хлебных крошек
     * @param bool $markLast отмечать ли активный элемент (удаляется url)
     * @return array
     */
    public function getBreadCrumbs($markLast = true)
    {

        $num = count($this->_breadCrumbs);

        if($markLast AND $num > 0)
            unset($this->_breadCrumbs[$num-1]["url"]);

        return $this->_breadCrumbs;
    }



    /**
     * Добавляет элементы к хлебным крошкам
     * @param array $items масив элементов хлебных крошек
     */

    public function addBreadCrumbs($items)
    {

        $this->breadCrumbs = array_merge($this->getBreadCrumbs(false), $items);

    }

    /**
     * Добавляет элемент к хлебным крошкам
     * @param array $item элемент хлебной крошки
     * @link http://www.yiiframework.com/doc-2.0/yii-widgets-breadcrumbs.html
     */

    public function addBreadCrumb($item)
    {

        $this->addBreadCrumbs([$item]);

    }

    /**
     * Регистрирует мета-теги
     * @param \lo\core\db\ActiveRecord $model
     */

    public function registerMetaTags($model)
    {

        if ($model->hasAttribute("title") AND !empty($model->title))
            $this->title = $model->title;
        elseif ($model->hasAttribute("name"))
            $this->title = $model->name;

        if ($model->hasAttribute("keywords"))
            $this->registerMetaTag(["name" => "keywords", "content" => $model->keywords]);

        if ($model->hasAttribute("description"))
            $this->registerMetaTag(["name" => "description", "content" => $model->description]);

    }

}