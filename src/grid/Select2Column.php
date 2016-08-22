<?php

namespace lo\core\grid;

use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\select2\Select2;

/**
 * Class Select2Column
 * Фильтр
 * @package lo\core\grid
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Select2Column extends DataColumn
{
    public $loadUrl = [];
    public $initValueText;

    /**
     * @inheritdoc
     */

    protected function renderFilterCellContent()
    {
        $widgetOptions = [
            'initValueText' => $this->initValueText, // set the initial display text
            'model' => $this->grid->filterModel,
            'attribute' => $this->attribute,
            'options' => ['multiple' => true],
        ];

        $ajaxWidgetOptions = [];

        if ($this->loadUrl) {
            $url = Url::to($this->loadUrl);
            $ajaxWidgetOptions = [
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 2,
                    'ajax' => [
                        'url' => $url,
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(data) { return data.text; }'),
                    'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                ]
            ];
        }

        return Select2::widget(ArrayHelper::merge($widgetOptions, $ajaxWidgetOptions));
    }

}