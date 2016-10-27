<?php

namespace lo\core\inputs;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Html;

/**
 * Class DropDownInput
 * Выпадающий список
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Select2AjaxInput extends DropDownInput {

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index индекс модели при табличном вводе
     * @return string
     */

    public $loadUrl=[];

    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $relation = $this->modelField->relation;
        $attr = $this->modelField->attr;
        $model = $this->modelField->model;
        $options = ArrayHelper::merge($this->options, $options);
        $this->fieldId = Html::getInputId($model, $attr);

        $this->setFieldTemplate();

        $url = Url::to($this->loadUrl);

        $val = ArrayHelper::getValue($model, "{$relation}.name");
        $val = $val ? $val : '---';

        $widgetOptions = ArrayHelper::merge([
            'initValueText' => $val, // set the initial display text
            'options' => [
                'prompt'=>'',
                'id' => $this->fieldId,
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



        return $form->field($model, $this->getFormAttrName($index, $attr), $this->formTemplate)->widget(
            Select2::className(), $widgetOptions
        );
    }

    protected function registerJs(){
        $theme = Select2::THEME_DEFAULT;
        $js = <<<JS

$('#add-$this->fieldId').on('kbModalSubmit', function(event, data, status, xhr) {
    console.log('kbModalSubmit' + status);
    if(status){
        $(this).modal('toggle');
        $('#$this->fieldId').html('').select2({
            theme: '$theme',
            data:
            [
               {id: data['id'], text: data['name']}
            ]
        });
    }
});

JS;

        $view=\Yii::$app->getView();
        $view->registerJs($js);
    }
} 