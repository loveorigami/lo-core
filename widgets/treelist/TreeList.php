<?php
namespace lo\core\widgets\treelist;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
use lo\core\widgets\App;

/**
 * Class TreeList
 * Виджет для вывода дерева. Отображаемая сущность должна наследовать \lo\core\db\TActiveRecord
 * @package app\modules\main\widgets\treelist
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class TreeList extends Menu
{

    /**
     * @var string имя класса модели
     */
    public $modelClass;

    /**
     * @var int идентификатор родительского раздела
     */
    public $parentId;

    /**
     * @var callable функция возвращающая url модели. Принимает аргументом модель для которой необходимо создать url
     */
    public $urlCreate;

    /**
     * @var callable функция для модификации запроса. Принимает аргументом \common\db\TActiveQuery
     */
    public $queryModify;

    /**
     * @var int глубина отображения
     */
    public $level = 1;

    /**
     * @var string имя выводимого атрибута
     */
    public $labelAttr = "name";
    public $slugAttr = "slug";


    /**
     * @var string the CSS class to be appended to the active menu item.
     */
    public $activeCssClass = 'active';
    /**
     * @var boolean whether to automatically activate items according to whether their route setting
     * matches the currently requested route.
     * @see isItemActive()
     */
    public $activateItems = true;
    /**
     * @var boolean whether to activate parent menu items when one of the corresponding child menu items is active.
     * The activated parent menu items will also have its CSS classes appended with [[activeCssClass]].
     */
    public $activateParents = false;


    /**
     * @inheritdoc
     */
    public function init()
    {

        if(!$this->items) {
            $class = $this->modelClass;

            $parent = $class::find()->published()->where(["id" => $this->parentId])->one();

            if (!$parent)
                return false;

            $level = $parent->level + $this->level;

            $query = $parent->children()->published()->andWhere("level <= :level", [":level" => $level]);

            if (is_callable($this->queryModify)) {
                $func = $this->queryModify;
                $func($query);
            }

            $items = $query->asArray()->all();

            $this->items = $this->nestedToNodes($items);

        }

    }

    public function nestedToNodes($nested_array){

        $result = [];
        $level = 0;
        $stack = [];

        foreach ($nested_array as $node) {

            $item = $node;
            $level = count($stack);

            while ($level > 0 && $stack[$level - 1]['level'] >= $item['level']) {
                array_pop($stack);
                $level--;
            }

            if ($level == 0) {
                $i = count($result);
                $result[$i] = $item;
                $result[$i]['url'] = $this->getUrl($item);
                $result[$i]['label'] = $item[$this->labelAttr];
                $stack[] = &$result[$i];
            } else {
                $i = count($stack[$level - 1]['items']);
                $stack[$level - 1]['items'][$i] = $item;
                $stack[$level - 1]['items'][$i]['url'] = $this->getUrl($item);
                $stack[$level - 1]['items'][$i]['label'] = $item[$this->labelAttr];
                $stack[] = &$stack[$level - 1]['items'][$i];
            }
        }

        $tree = $result;

        return $tree;
    }

    protected function getUrl($item){
        if (is_callable($this->urlCreate)){
            $urlCreate = $this->urlCreate;
            return $urlCreate($item[$this->slugAttr]);
        }
          return false;
    }
}