<?php
namespace lo\core\widgets\treelist;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
use yii\helpers\ArrayHelper;
use lo\core\widgets\App;

/**
 * Class TreeList
 * Виджет для вывода дерева. Отображаемая сущность должна наследовать \lo\core\db\TActiveRecord
 * @package app\modules\main\widgets\treelist
 * @author Lukyanov Andrey <loveorigami@mail.ru>
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
     * @var callable функция для модификации запроса. Принимает аргументом \lo\core\db\TActiveQuery
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
     * @var string prefix for the icon in [[items]]. This string will be prepended
     * before the icon name to get the icon CSS class. This defaults to `glyphicon glyphicon-`
     * for usage with glyphicons available with Bootstrap.
     */
    public $iconPrefix = 'fa fa-';

    /**
     * @var string indicator for a menu sub-item
     */
    public $indItem = '&raquo; ';
    /**
     * @var string indicator for a opened sub-menu
     */


    public $firstLevelCssClass ='list-group-item';

    protected $parentLevel;
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

            $this->parentLevel = $parent->level;
            $level = $parent->level + $this->level;

            $query = $parent->children()->published()->andWhere("level <= :level", [":level" => $level]);

            if (is_callable($this->queryModify)) {
                $func = $this->queryModify;
                $func($query);
            }

            $items = $query->asArray()->all();

            $this->items = $this->nestedToNodes($items);

        }

        $this->activateParents = true;
        $this->linkTemplate = '<a href="{url}">{icon}{label}</a>';
        $this->submenuTemplate = "\n<ul class='kv-submenu'>\n{items}\n</ul>\n";
/*        $this->view->registerJs("
            $('.kv-toggle').click(function (event) {
                event.preventDefault(); // cancel the event
                //$(this).children('.opened').toggle()
                //$(this).children('.closed').toggle()
                $(this).parent().children('ul').toggle()
                $(this).parent().toggleClass('active')
                return false;
            });
        ");
        $this->view->registerCss("
            .kv-submenu {
                display: none;
            }

            li.active .kv-submenu {
                display: block;
            }

        ");*/


    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];

            if($this->parentLevel + 1 == $item['level']){
                $class[] = $this->firstLevelCssClass;
            }
            if (!empty($item['items'])) {
                $class[] = 'list-toggle';
            }

            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }

            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }

            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
                $menu .= strtr($submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                ]);
            }
            if ($tag === false) {
                $lines[] = $menu;
            } else {
                $lines[] = Html::tag($tag, $menu, $options);
            }
        }

        return implode("\n", $lines);
    }


    /**
     * Renders the content of a side navigation menu item.
     *
     * @param array $item the menu item to be rendered. Please refer to [[items]] to see what data might be in the item.
     * @return string the rendering result
     * @throws InvalidConfigException
     */
    protected function renderItem($item)
    {
        //$this->validateItems($item);
        $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
        $url = Url::to(ArrayHelper::getValue($item, 'url', '#'));

/*            if (empty($item['items'])) {
                $template = str_replace('{icon}', $this->indItem . '{icon}', $template);
            } else {
                $template = isset($item['template']) ? $item['template'] :'<a href="{url}" class="kv-toggle">{icon}{label}</a>';
            }*/

        $icon = empty($item['icon']) ? '<i class="' . $this->iconPrefix . 'angle-double-right"></i> &nbsp;' : '<i class="' . $this->iconPrefix . $item['icon'] . '"></i> &nbsp;';
        unset($item['icon']);
        return strtr($template, [
            '{url}' => $url,
            '{label}' => $item['label'],
            '{icon}' => $icon
        ]);
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