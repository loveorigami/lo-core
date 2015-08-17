<?php
namespace lo\core\admin\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class Menu
 * @package backend\components\widget
 */
class Menu extends \yii\widgets\Menu
{
    /**
     * @var string
     */
    public $linkTemplate = "<a href=\"{url}\">\n{icon}\n{label}\n{right-icon}</a>";

    /**
     * @var string
     */
    public $labelTemplate = '{icon}\n{label}'; // {badge}

    /**
     * @var string
     */
    public $badgeTag = 'span';
    /**
     * @var string
     */
    public $badgeClass = 'label pull-right';
    /**
     * @var string
     */
    public $badgeBgClass;

    /**
     * @var string
     */
    public $parentRightIcon = '<i class="fa fa-angle-left pull-right"></i>';

    /**
     * @inheritdoc
     */
    public function getList()
    {
        $model = \Yii::createObject('\lo\modules\core\models\Menu');
        $menu = $model->getMenu();


        $result = array();
        $level = 0;
        $stack = array();
        echo  Yii::$app->user->can("/site/index");
        foreach ($menu as $node) {

            $item = $node;
            $item['label'] = $node['name'].Yii::$app->user->can("'".$node['slug']."'");
            $item['url'] = self::parseRoute($node['slug']);
            $item['visible'] = self::access($item['slug']);
            //  \mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback);

            $level = count($stack);

            while($level > 0 && $stack[$level - 1]['level'] >= $item['level']) {
                array_pop($stack);
                $level--;
            }

            if ($level == 0) {
                $i = count($result);
                $result[$i] = $item;
                $stack[] = & $result[$i];
            } else {
                $i = count($stack[$level - 1]['items']);
                $stack[$level - 1]['items'][$i] = $item;
                $stack[] = & $stack[$level - 1]['items'][$i];
            }
        }

        $tree = $result;

        return $tree;

        //var_dump($arr);
       // return $arr;
    }

    /**
     * Access
     * @param  string $route
     * @return mixed
     */
    protected static function access($url)
    {
        if($url=='#'){
            return true;
        }

        return Yii::$app->user->can($url);
    }

    /**
     * Parse route
     * @param  string $route
     * @return mixed
     */
    protected static function parseRoute($route)
    {
        if (!empty($route)) {
            $url = [];
            $r = explode('&', $route);
            $url[0] = $r[0];
            unset($r[0]);
            foreach ($r as $part) {
                $part = explode('=', $part);
                $url[$part[0]] = isset($part[1]) ? $part[1] : '';
            }
            return $url;
        }

        return '#';
    }

    protected function renderItem($item)
    {
        //$item['badgeOptions'] = isset($item['badgeOptions']) ? $item['badgeOptions'] : [];

        /*  if (!ArrayHelper::getValue($item, 'badgeOptions.class')) {
            $bg = isset($item['badgeBgClass']) ? $item['badgeBgClass'] : $this->badgeBgClass;
            $item['badgeOptions']['class'] = $this->badgeClass . ' ' . $bg;
        }*/

        if (isset($item['items']) && !isset($item['right-icon'])) {
            $item['right-icon'] = $this->parentRightIcon;
        }

        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

            return strtr($template, [
                //'{badge}' => isset($item['badge']) ? Html::tag('small', $item['badge'], $item['badgeOptions']) : '',
                '{icon}' => isset($item['icon']) ? "<i class='fa fa-" . $item["icon"] . "'></i>" : "<i class='fa fa-plus'></i>",
                '{right-icon}' => isset($item['right-icon']) ? $item['right-icon'] : '',
                '{url}' => Url::to($item['url']),
                '{label}' => $item['label'],
            ]);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

            return strtr($template, [
               //'{badge}' => isset($item['badge'])? Html::tag('small', $item['badge'], $item['badgeOptions']) : '',
                '{icon}' => isset($item['icon']) ? $item['icon'] : '',
                '{right-icon}' => isset($item['right-icon']) ? $item['right-icon'] : '',
                '{label}' => $item['label'],
            ]);
        }
    }

    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = Yii::getAlias($item['url'][0]);

            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }
//echo $this->route; page/item/update
 //  echo rtrim($route, '/index');
            $cur = preg_replace('~[^/]+$~s', '', $this->route);
            //$cur2 =  substr($this->route,strrpos($this->route,"/")+1);
            $cur2 =  substr($route, 0, strrpos($route, '/')+1);
           // echo $cur.'<br>';
           // echo ltrim($cur2,'/').'<br>';

            if (ltrim($cur2, '/') !== $cur) {
                return false;
            }
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                $params = $item['url'];
                unset($params[0]);
                foreach ($params as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }
}
