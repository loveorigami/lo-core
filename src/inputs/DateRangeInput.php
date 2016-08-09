<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\date\DatePicker;
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
     * ```php
     *  public $options = [
     *      'language' => 'ru',
     *      'size' => 'ms',
     *      'template' => '{input}',
     *      'inline' => false,
     *      'clientOptions' => [
     *          'allowInputToggle' => false,
     *          'sideBySide' => true,
     *          'locale' => 'ru',
     *          'format' => 'yyyy-mm-dd',
     *          'startView' => 2,
     *          'minView' => 0,
     *          'maxView' => 1,
     *          'autoclose' => true,
     *          'linkFormat' => 'HH:ii P', // if inline = true
     *          'format' => 'HH:ii P', // if inline = false
     *          'todayBtn' => true,
     *          'widgetPositioning' => [
     *              'horizontal' => 'auto',
     *              'vertical' => 'auto'
     *          ]
     *      ]
     *  ];
     * ```
     * @var array
     */
    protected $defaultOptions = [
        'language' => 'ru',
        'inline' => false,
        'clientOptions' => [
            'allowInputToggle' => false,
            'sideBySide' => true,
            'locale' => 'ru',
            'format' => 'yyyy-mm-dd',
            'autoclose' => true,
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
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->defaultOptions, $this->options, $options);

        $widgetOptions = ArrayHelper::merge([
            "options" => ["class" => "form-control"]],
            $this->widgetOptions, $options
        );

        $fieldOptions = [
            "options" => ["class" => "form-group col-xs-6"],
        ];


        /*        $html = Html::beginTag('div', ['class' => 'row']);
                $html .= $form->field($this->modelField->model, $this->fromAttr, $fieldOptions)->widget(DateTimePicker::class, $widgetOptions);
                $html .= $form->field($this->modelField->model, $this->toAttr, $fieldOptions)->widget(DateTimePicker::class, $widgetOptions);
                $html .= Html::endTag('div');*/

// Usage with model and Active Form (with no default initial value)
        $html = $form->field($this->getModel(), $this->fromAttr)->widget(DatePicker::class, [
            'attribute2' => $this->toAttr,
            'type' => DatePicker::TYPE_RANGE,
            'options' => [
                'placeholder' => 'Enter birth date ...',
            ],
            'pluginOptions' => [
                'autoclose' => true
            ]
        ]);

        return $html;
    }
}