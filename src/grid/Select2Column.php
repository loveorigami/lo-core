<?php

namespace lo\core\grid;

use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
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
    /**
     * Load json data from url
     * @var array
     */
    public $loadUrl;

    public $initValueText;

    /**
     * @inheritdoc
     */
    protected function renderFilterCellContent()
    {
        $ajaxWidgetOptions = [];

        $widgetOptions = [
            'initValueText' => $this->initValueText, // set the initial display text
            'model' => $this->grid->filterModel,
            'attribute' => $this->attribute,
            'options' => [
                'multiple' => false,
                'id' => 'fs-'.$this->attribute,
            ],
        ];

        if ($this->loadUrl) {
            $ajaxWidgetOptions = [
                'pluginOptions' => [
                    'options' => [
                        'prompt' => ' --- ',
                    ],
                    'allowClear' => true,
                    'placeholder' => [
                        'id' => '',
                        'placeholder' => 'Select...'
                    ],
                    'minimumInputLength' => 2,
                    'ajax' => [
                        'url' => $this->loadUrl,
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(data) { if (data.placeholder) return data.placeholder; return data.text; }'),
                    'templateSelection' => new JsExpression('function (data) { if (data.placeholder) return data.placeholder; return data.text; }'),
                ]
            ];
        }

        return Select2::widget(ArrayHelper::merge($widgetOptions, $ajaxWidgetOptions));
    }

}