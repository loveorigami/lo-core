<?php

namespace lo\core\inputs;

use lo\widgets\daterange\DateRangeInline;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class DateRangeInput
 * Поле ввода диапазона дат
 * @package lo\core\inputs
 */
class DateRangeInlineInput extends BaseInput
{
    /**
     * Опции по умолчанию
     * @var array
     */
    protected $defaultOptions = [
        'fromAttr' => 'date_from',
        'toAttr' => 'date_to',
        'pluginOptions' => [
            'language' => 'ru',
            'format' => 'YYYY-MM-DD',
            'separator' => ' ~ '
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
        $widgetOptions = ArrayHelper::merge(
            $this->defaultOptions, $this->widgetOptions
        );

        return $form->field($this->getModel(), $this->getAttr())->widget(DateRangeInline::class, $widgetOptions);

    }
}