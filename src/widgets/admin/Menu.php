<?php

namespace lo\core\widgets\admin;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class Menu
 * @package lo\core\widgets\admin
 */
class Menu extends \yii\widgets\Menu
{
    /** @var string */
    public $linkTemplate = "<a href=\"{url}\">\n{icon}\n{label}\n{right-icon}\n{badge}</a>";

    /** @var string */
    public $labelTemplate = '{icon}\n{label}';
    public $submenuTemplate = "\n<ul class='treeview-menu' {show}>\n{items}\n</ul>\n";
    public $activateParents = true;
    public $defaultIconHtml = '<i class="fa fa-circle-o"></i> ';

    /** @var string */
    public $parentRightIcon = '<i class="fa fa-angle-left pull-right"></i>';


    /**
     * @inheritdoc
     */
    protected function renderItem2($item)
    {
        if (isset($item['items'])) {
            $labelTemplate = '<a href="{url}">{icon} {label} <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
            $linkTemplate = '<a href="{url}">{icon} {label} <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
        } else {
            $labelTemplate = $this->labelTemplate;
            $linkTemplate = $this->linkTemplate;
        }

        $replacements = [
            '{label}' => strtr($this->labelTemplate, ['{label}' => $item['label'],]),
            '{icon}' => empty($item['icon']) ? $this->defaultIconHtml
                : '<i class="' . self::$iconClassPrefix . $item['icon'] . '"></i> ',
            '{url}' => isset($item['url']) ? Url::to($item['url']) : 'javascript:void(0);',
        ];

        $template = ArrayHelper::getValue($item, 'template', isset($item['url']) ? $linkTemplate : $labelTemplate);
        return strtr($template, $replacements);
    }

    /**
     * @inheritdoc
     */
    protected function renderItem($item)
    {

        if (isset($item['items']) && !isset($item['right-icon'])) {
            $item['right-icon'] = $this->parentRightIcon;
        }

        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

            return strtr($template, [
                '{icon}' => isset($item['icon']) ? $item['icon'] : '',
                '{right-icon}' => isset($item['right-icon']) ? $item['right-icon'] : '',
                '{url}' => Url::to($item['url']),
                '{label}' => $item['label'],
            ]);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);
            return strtr($template, [
                '{icon}' => isset($item['icon']) ? $item['icon'] : '',
                '{right-icon}' => isset($item['right-icon']) ? $item['right-icon'] : '',
                '{label}' => $item['label'],
            ]);
        }
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
                $menu .= strtr($this->submenuTemplate, [
                    '{show}' => $item['active'] ? "style='display: block'" : '',
                    '{items}' => $this->renderItems($item['items']),
                ]);
                if (isset($options['class'])) {
                    $options['class'] .= ' treeview';
                } else {
                    $options['class'] = 'treeview';
                }
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }
        return implode("\n", $lines);
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

            if (!$route) return false;

            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }

            $this->route = str_replace('/update', '/index', $this->route);
            if (ltrim($route, '/') !== $this->route) {
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
