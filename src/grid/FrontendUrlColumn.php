<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 27.06.2017
 * Time: 17:29
 */

namespace lo\core\grid;

use lo\core\helpers\FA;
use lo\core\url\FrontendUrlHelper;
use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;

class FrontendUrlColumn extends DataColumn
{
    public $route;
    public $contentOptions = ['class' => 'text-center'];
    public $headerOptions = ['max-width' => '40'];
    public $format = 'raw';
    public $label = '';
    public $filter = false;


    protected function renderDataCellContent($model, $key, $index)
    {
        $value = $this->getDataCellValue($model, $key, $index);
        return Html::a(FA::i(FA::_LINK),
            FrontendUrlHelper::url([$this->route, $this->attribute => $value]), [
                'title'=> Yii::t('core', 'on site'),
                'target' => '_blank',
                'class' => 'btn btn-primary btn-xs',
                'data' => [
                    'pjax' => 0
                ]
            ]
        );


    }
}