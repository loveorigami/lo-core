<?php

namespace lo\core\modules\main\widgets\menu;

use lo\core\db\tree\TActiveRecord;
use lo\modules\main\models\Menu as MenuModel;
use lo\core\widgets\App;

/**
 * Class Menu
 * @package lo\core\modules\main\widgets\menu
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Menu extends App
{
    /** @var int идентификатор родительского пункта меню */
    public $parentId;

    /** @var string символьный идентификатор родительского пункта меню */
    public $parentCode;

    /** @var int глубина вложенности */
    public $depth = 1;

    /** @var array html - атрибуты корневого тега ul меню */

    public $options = [];
    public $sub_options = [];

    /** @var string имя класса активного пункта меню */
    public $actClass = "active";

    /** @var Menu[] массив моделей меню */
    protected $models = [];

    /** @var int уровень вложенности родительского пункта меню */
    protected $parentLevel;

    /**
     * @inheritdoc
     * Инициализация
     */
    public function init()
    {
        if (!$this->isShow()) {
            return null;
        }

        MenuAsset::register($this->view);
        $this->findModels();
    }

    /**
     * Поиск моделей меню
     * @return void
     */
    protected function findModels()
    {
        $parentQuery = MenuModel::find()->published();

        /** @var TActiveRecord $parent */
        if ($this->parentId) {
            $parent = $parentQuery->where(["id" => $this->parentId])->one();
        } elseif ($this->parentCode) {
            $parent = $parentQuery->where(["code" => $this->parentCode])->one();
        }

        if (empty($parent)) {
            return null;
        }

        $this->parentLevel = $parent->level;
        $this->models = $parent->getTreeByStatus($parent, $this->depth, MenuModel::STATUS_PUBLISHED);
    }

    /**
     * @inheritdoc
     * Запуск виджета
     */
    public function run()
    {
        if (!$this->isShow() OR empty($this->models)) {
            return null;
        }

        return $this->render($this->tpl, [
            "models" => $this->models,
            "parentLevel" => $this->parentLevel,
            "options" => $this->options,
            "sub_options" => $this->sub_options,
            "actClass" => $this->actClass,
        ]);
    }
}