<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use lo\core\widgets\awcheckbox\AwesomeCheckbox;

/**
 * Class CheckBoxInputA
 * Чекбокс c виджетом Awesome Bootstrap Checkbox
 * @package lo\core\inputs
 */
class CheckBoxInputA extends CheckBoxInput {

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */

    public $options = [
        'type'=>AwesomeCheckbox::TYPE_CHECKBOX,
        'style'=>AwesomeCheckbox::STYLE_PRIMARY,
    ];

    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {

        $options = ArrayHelper::merge($this->options, $this->widgetOptions, $options);

        return $form->field($this->modelField->model, $this->getFormAttrName($index, $this->modelField->attr))->widget(AwesomeCheckbox::class, $options);
    }

}