<?php
namespace lo\core\widgets;

use yii\helpers\Html;
use yii\jui\JuiAsset;

/**
 * Class SortedTags
 * Реализует выбор тегов из выпадающего списка с возможностью их сортировки. Формирует данные в виде строки вида "#id название"
 * @package common\widgets
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class SortedTags extends \dosamigos\selectize\SelectizeTextInput
{

    /**
     * @inheritdoc
     */
    public function registerClientScript()
    {
        parent::registerClientScript();
        JuiAsset::register($this->view);

    }

}