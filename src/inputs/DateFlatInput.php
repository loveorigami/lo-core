<?php

namespace lo\core\inputs;

use bs\Flatpickr\FlatpickrWidget;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class DateInput
 * Поле ввода диапазона дат
 * @package lo\core\inputs
 */
class DateFlatInput extends BaseInput
{
    /**
     * Опции по умолчанию
     * @var array
     */
    protected $defaultOptions = [
        'locale' => 'ru',
        'groupBtnShow' => true,
        'options' => [
            'class' => 'form-control',
        ],
        // https://chmln.github.io/flatpickr/options/
        'clientOptions' => [
            'altInput' => true
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
        $widgetOptions = ArrayHelper::merge($this->defaultOptions, $this->widgetOptions, ["options" => $options]);
        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(FlatpickrWidget::class, $widgetOptions);
    }
}