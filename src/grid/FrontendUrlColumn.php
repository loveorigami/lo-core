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

    /**
     * For url param in route
     * ['pattern' => 'page/<pageSlug>', 'route' => 'page/view'],
     * @var string
     */
    public $params;

    /**
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     * @return string
     */
    protected function renderDataCellContent($model, $key, $index): string
    {
        $value = $this->getDataCellValue($model, $key, $index);
        $attr = $this->params ??  $this->attribute;

        if ($this->route) {
            $url = [$this->route, $attr => $value];
        } else {
            $url = [$value];
        }

        return Html::a(FA::i(FA::_LINK),
            FrontendUrlHelper::url($url), [
                'title' => Yii::t('core', 'on site'),
                'target' => '_blank',
                'class' => 'btn btn-primary btn-xs',
                'data' => [
                    'pjax' => 0
                ]
            ]
        );


    }
}