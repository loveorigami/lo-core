<?php

namespace lo\core\inputs;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/**
 * Class DropDownInput
 * Выпадающий список
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Select2AjaxInput extends BaseInput {

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */

    public $loadUrl=[];

    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $relation = $this->modelField->relation;
        $attr = $this->modelField->attr;

        $url = \yii\helpers\Url::to($this->loadUrl);

        $options = ArrayHelper::merge($this->options, $options);

        $val = ArrayHelper::getValue($this->modelField->model, "{$relation}.name");
        $val = $val ? $val : '---';

        $widgetOptions = ArrayHelper::merge([
            'initValueText' => $val, // set the initial display text
            'options' => [
                'prompt'=>''
            ],
            'pluginOptions' => [
                'allowClear' => false,
                'minimumInputLength' => 2,
                'ajax' => [
                    'url' => $url,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(data) { return data.text; }'),
                'templateSelection' => new JsExpression('function (data) { return data.text; }'),
            ],
        ], $this->widgetOptions, ["options" => $options]);



        return $form->field($this->modelField->model, $this->getFormAttrName($index, $attr))->widget(
            Select2::className(), $widgetOptions
        );
    }

} 