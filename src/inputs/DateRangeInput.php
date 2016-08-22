<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use lo\core\widgets\DatePicker;
use yii\widgets\ActiveForm;
use yii\base\InvalidConfigException;

/**
 * Class DateRangeInput
 * Поле ввода диапазона дат
 * @package lo\core\inputs
 */
class DateRangeInput extends BaseInput
{
    /**
     * @var string атрибут от
     */
    public $fromAttr;

    /**
     * @var string атрибут до
     */
    public $toAttr;

    /**
     * Опции по умолчанию
     * @var array
     */
    protected $defaultOptions = [
        'language' => 'ru',
        'type' => DatePicker::TYPE_RANGE,
        'options' => ['placeholder' => 'Start date'],
        'options2' => ['placeholder' => 'End date'],
        'separator' => '-',
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => false,
            'todayHighlight' => true,
            'todayBtn' => true,
        ]
    ];

    public function init()
    {
        parent::init();

        if (empty($this->fromAttr) || empty($this->toAttr)) {
            throw new InvalidConfigException("Properties 'fromAttr', 'toAttr' can`t be blank");
        }
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
            $this->defaultOptions, $this->widgetOptions, ['attribute2' => $this->toAttr, 'options'=>$options]
        );

        return $form->field($this->getModel(), $this->fromAttr)->widget(DatePicker::class, $widgetOptions);

    }
}