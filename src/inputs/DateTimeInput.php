<?php

namespace lo\core\inputs;

use lo\core\helpers\DateHelper;
use lo\core\widgets\DateTimePicker;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class DateTimeInput
 * Поле ввода диапазона дат
 * @package lo\core\inputs
 */
class DateTimeInput extends BaseInput
{
    /**
     * Опции по умолчанию
     * @var array
     */
    protected $defaultOptions = [
        'language' => 'ru',
        'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date'],
        'pluginOptions' => [
            'format' => DateHelper::DP_DATETIME_FORMAT,
            'autoclose' => true,
            'todayHighlight' => true,
            'todayBtn' => true,
        ]
    ];

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
        $widgetOptions = ArrayHelper::merge($this->defaultOptions, $this->widgetOptions, ["options"=>$options]);
        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(DateTimePicker::class, $widgetOptions);
    }
}