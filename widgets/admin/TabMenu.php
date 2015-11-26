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
        foreach($this->items as $key=>$value){
            $this->items[$key]['label'] = Yii::t('backend', $this->items[$key]['label']);
        }

        return Nav::widget([
            'options' => [
                'class' => 'nav-tabs',
                'style' => 'margin-bottom: 15px'
            ],
            'items' => $this->items
        ]);
    }

/*    protected function labels($items){
        foreach($items as $key=>$value){
            var_dump($this->items[$key]);
        }
    }*/
}
