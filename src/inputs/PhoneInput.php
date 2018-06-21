<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/**
 * Class PhoneInput
 *
 * @package lo\core\inputs
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class PhoneInput extends BaseInput
{
    /**
     * Формирование Html кода поля для вывода в форме
     *
     * @param ActiveForm $form    объект форма
     * @param array      $options массив html атрибутов поля
     * @param bool|int   $index   индекс модели при табличном вводе
     * @return string
     * @throws \Exception
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        //$options = ArrayHelper::merge($this->options, $options);
        $widgetOptions = ArrayHelper::merge([
            'mask' => '+7 (999) 999-99-99',
        ], $this->widgetOptions);

        return $form->field(
            $this->getModel(),
            $this->getFormAttrName($index, $this->getAttr())
        )->widget(MaskedInput::class, $widgetOptions);
    }
}
