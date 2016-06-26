<?php

namespace lo\core\inputs;

use lo\core\widgets\DependDropDown;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use karnbrockgmbh\modal\Modal;
use yii\helpers\Url;
use yii\helpers\Html;


/**
 * Class DropDownInput
 * Выпадающий список
 * @package lo\core\inputs
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class DropDownInput extends BaseInput
{

    protected $formTemplate = [];
    public $modalUrl = '';
    public $fieldId = '';

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $attr = $this->modelField->attr;
        $model = $this->modelField->model;
        $options = ArrayHelper::merge($this->options, $options);
        $this->fieldId = Html::getInputId($model, $attr);

        $this->getTpl();

        $widgetOptions = ArrayHelper::merge(
            [
                "options" => [
                    "class" => "form-control",
                    "prompt" => "",
                    "encode" => false,
                    "id" => $this->fieldId,
                ],
                "data" => $this->modelField->getDataValue()
            ],
            $this->widgetOptions,
            ["options" => $options]
        );

        return $form->field($model, $this->getFormAttrName($index, $attr), $this->formTemplate
        )->widget(
            DependDropDown::className(), $widgetOptions
        );
    }

    protected function getTpl()
    {
        if (!$this->modalUrl) return false;
        Modal::begin([
            'id' => 'add-' . $this->fieldId,
            'url' => Url::to($this->modalUrl), // Ajax view with form to load
            'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
            // ... any other yii2 bootstrap modal option you need
            'header' => 'Add Author',
            'size' => 'modal-lg',
            'clientOptions' => false,
        ]);

        Modal::end();

        $this->formTemplate = [
            'template' => '{label}
                    <div class="input-group">{input}
                        <div class="input-group-btn">
                                <a href="#" data-toggle="modal" data-target="#add-' . $this->fieldId . '" class="btn btn-primary">
                                    <i class="fa fa-plus"></i>
                                </a>
                        </div>
                    </div>
                    {error}{hint}',
        ];
        $this->setJs();
    }

    protected function setJs(){
        $js = <<<JS

$('#add-$this->fieldId').on('kbModalSubmit', function(event, data, status, xhr) {
    console.log('kbModalSubmit' + status);
    if(status){
        $(this).modal('toggle');
        $('#$this->fieldId').append($('<option></option>').attr('value', data['id']).prop("selected","selected").text(data['name']));
    }
});

JS;

        $view=\Yii::$app->getView();
        $view->registerJs($js);
    }

} 