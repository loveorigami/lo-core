<?php
namespace lo\core\widgets;

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\jui\JuiAsset;

/**
 * Class SortedTags
 * Реализует выбор тегов из выпадающего списка с возможностью их сортировки. Формирует данные в виде строки вида "#id название"
 * @package common\widgets
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class SortedTags extends Select2
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

    }

    /**
     * @inheritdoc
     */
    public function registerAssets()
    {
        parent::registerAssets();

        JuiAsset::register($this->view);

        $id = $this->options["id"];

        $this->view->registerJs("
            $('ul.select2-selection__rendered').sortable({
                containment: 'parent',
            });
        ");

    }

}