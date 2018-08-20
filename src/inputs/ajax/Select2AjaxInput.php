<?php

namespace lo\core\inputs\ajax;

use kartik\select2\Select2;
use lo\core\db\fields\ajax\AjaxField;
use Yii;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/**
 * Class Select2AjaxInput
 * Ajax выпадающий список
 *  "cat_id" => [
 *      "definition" => [
 *          "class" => fields\AjaxOneField::class,
 *          "inputClass" => inputs\Select2AjaxInput::class,
 *          'loadUrl' => ['content/category/list'], // action ListId
 *          "inputClassOptions" => [
 *              'widgetOptions'=>[
 *                  'pluginOptions' => [
 *                      'allowClear' => true
 *                  ]
 *              ],
 *          ],
 *          "title" => Yii::t('backend', 'Category'),
 *          "relationName" => 'category',
 *          "showInGrid" => false,
 *          "showInFilter" => true,
 *      ],
 *      "params" => [$this->owner, "cat_id"]
 *  ],
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Select2AjaxInput extends AjaxInput
{
    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index индекс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $options);

        $widgetOptions = ArrayHelper::merge(
            $this->defaultWidgetOptions(),
            $this->widgetOptions, ['options' => $options]
        );
        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()), $this->fieldTemplate)->widget(
            Select2::class, $widgetOptions
        );
    }

    /**
     * @return array
     */
    protected function defaultWidgetOptions(): array
    {
        /** @var AjaxField $modelField */
        $modelField = $this->modelField;
        $model = $modelField->model;
        $relation = $modelField->relationName;
        $attr = $modelField->gridAttr;

        $val = ArrayHelper::getValue($model, "{$relation}.$attr");
        $val = $val ? $val : '---';

        return [
            'initValueText' => $val, // set the initial display text
            'options' => [
                ['placeholder' => 'Select...'],
                'id' => $this->fieldId,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => [
                    'id' => '',
                    'placeholder' => 'Select...'
                ],
                'minimumInputLength' => 2,
                'ajax' => [
                    'url' => $this->getLoadUrl(),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(data) { return data.text; }'),
                'templateSelection' => new JsExpression('function (data) { return data.text; }'),
            ],
        ];
    }

    /**
     * register js
     */
    protected function registerJs()
    {
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

        $view = Yii::$app->getView();
        $view->registerJs($js);
    }

} 
