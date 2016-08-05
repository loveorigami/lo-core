<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class TextInput
 * Текстовое поле
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TextInput extends BaseInput {

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $options);
        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()), $this->widgetOptions)->textInput($options);
    }
} 