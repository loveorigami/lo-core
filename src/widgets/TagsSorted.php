<?php
namespace lo\core\widgets;

use yii\helpers\Html;
use yii\jui\JuiAsset;

/**
 * Class SortedTags
 * Реализует выбор тегов из выпадающего списка с возможностью их сортировки. Формирует данные в виде строки вида "#id название"
 * @package lo\core\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TagsSorted extends \dosamigos\selectize\SelectizeTextInput
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