<?php

namespace lo\core\inputs;

use kartik\touchspin\TouchSpin;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class NumberSpinInput
 * Поле ввода чисел
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class NumberSpinInput extends BaseInput
{
    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index индeкс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $options);

        $widgetOptions = ArrayHelper::merge(["options" => ["class" => "form-control"]], $this->widgetOptions, ["options" => $options]);

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(TouchSpin::class, $widgetOptions);
    }
}