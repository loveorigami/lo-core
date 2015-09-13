<?php
namespace lo\core\widgets\admin;

use Yii;
use yii\bootstrap\Nav;

/**
 * Class Menu
 * @package backend\components\widget
 */
class TabMenu extends \yii\widgets\Menu
{

    public $items = [];

    public function init()
    {
        $code = Yii::$app->controller->module->id;
        $module = Yii::$app->getModule($code);

        if (is_object($module)) {
            $this->items = $module->menuItems;
        }
    }

    public function run()
    {
        return Nav::widget([
            'options' => [
                'class' => 'nav-tabs',
                'style' => 'margin-bottom: 15px'
            ],
            'items' => $this->items
        ]);

    }
}
