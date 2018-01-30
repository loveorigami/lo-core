<?php

namespace lo\core\db\fields\ajax;

use kartik\editable\Editable;
use lo\core\grid\KEditableColumn;
use lo\core\inputs\ajax\Select2AjaxInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * Class AjaxOneField
 * Поле для связей Has One. Интерфейс привязки в форме в виде выпадающего списка с ajax выбором.
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class DropDownField extends AjaxField
{
    public $inputClass = Select2AjaxInput::class;

    /**
     * Редатироование в гриде
     * @return array
     */
    public function xEditableTest()
    {
        return [
            'class' => KEditableColumn::class,
            'format' => 'raw',
            "value" => function ($model) {
                return ArrayHelper::getValue($model, "{$this->relationName}.{$this->gridAttr}");
            },

            'editableOptions' => function ($model, $key, $index) {
                return [
                    'model' => $model,
                    'displayValue' => ArrayHelper::getValue($model, "{$this->relationName}.{$this->gridAttr}"),
                    'id' => 'hhh'.$index,
                    'size' => 'mg',
                    'formOptions' => [
                        'action' => $this->getEditableUrl()
                    ],
                    'inputType' => Editable::INPUT_SELECT2,
                    'submitOnEnter' => true,
                    'asPopover' => true,
                    'options' => [
                        'pluginOptions' => [
                            'minimumInputLength' => 2,
                            'ajax' => [
                                'url' => Url::to(['/geo/country/list']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(data) { if (data.placeholder) return data.placeholder; return data.text; }'),
                                'templateSelection' => new JsExpression('function (data) { if (data.placeholder) return data.placeholder; return data.text; }'),
                            ],
                        ],
                    ]
                ];
            },
        ];
    }

    /**
     * 'header' => 'product',
     * 'size' => 'mg',
     * 'formOptions' => [
     * 'action' => ['/planning/editproduct']
     * ],
     * 'inputType' => Editable::INPUT_SELECT2,
     * 'submitOnEnter' => false,
     * 'pjaxContainerId' => 'planner_grid',
     * 'asPopover' => true,
     * 'valueIfNull' => '<em>'.Yii::t('app', '(unknown)').'</em>',
     * 'options' => [
     * 'class' => 'form-control',
     * 'data' => ArrayHelper::map(Producten::find()->where(['type' => 'afvalstoffen'])->asArray()->all(), 'producten_id', 'product'),
     * 'pluginOptions' => [
     * 'multiple' => true,
     * 'placeholder' => 'Voeg hier een aantal toe.',
     * ],
     * ]
     */
}