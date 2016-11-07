<?php

namespace lo\core\inputs;

use lo\core\widgets\DependDropDown;
use lo\widgets\modal\AjaxModal;
use Yii;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Class DropDownInput
 * Выпадающий список
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class DropDownInput extends BaseInput
{
    public $fieldId;
    public $modalUrl;
    protected $fieldTemplate = [];

    public function init()
    {
        parent::init();

        $this->setFieldId();
        $this->setFieldTemplate();
        $this->setModal();
        $this->registerJs();
    }

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

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()), $this->fieldTemplate)->widget(DependDropDown::class, $widgetOptions);
    }

    /**
     * set fieldId
     */
    protected function setFieldId()
    {
        $this->fieldId = Html::getInputId($this->getModel(), $this->getAttr());
    }

    /**
     * set Modal
     */
    protected function setModal()
    {
        if (!$this->modalUrl) {
            return null;
        }

        AjaxModal::begin([
            'id' => 'add-' . $this->fieldId,
            'url' => Url::to($this->modalUrl), // Ajax view with form to load
            'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
            // ... any other yii2 bootstrap modal option you need
            'header' => 'New item',
            'size' => 'modal-lg',
            'clientOptions' => false,
            'options' => ['class' => 'header-success']
        ]);
        AjaxModal::end();
    }

    /**
     * @return null
     */
    protected function setFieldTemplate()
    {
        if (!$this->modalUrl) {
            return null;
        }

        $this->fieldTemplate = [
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
    }

    /**
     * Регистрация js
     */
    protected function registerJs()
    {
        $js = <<<JS

$('#add-$this->fieldId').on('kbModalSubmit', function(event, data, status, xhr) {
    if(status){
        $(this).modal('toggle');
        $('#$this->fieldId').append($('<option></option>').attr('value', data['id']).prop("selected","selected").text(data['name']));
    }
});

JS;
        $view = Yii::$app->getView();
        $view->registerJs($js);
    }

} 