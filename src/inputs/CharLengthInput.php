<?php

namespace lo\core\inputs;

use lo\core\widgets\charlength\CharLength;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class MaxLengthInput
 * Поле ввода чисел
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CharLengthInput extends BaseInput
{
    /**
     * Опции по умолчанию
     * @var array
     */
    protected $defaultOptions = [
        'type' => CharLength::INPUT_TEXTAREA,
        'options' => [
            'class' => 'form-control'
        ],
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

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))
            ->widget(CharLength::class, $widgetOptions);
    }
} 