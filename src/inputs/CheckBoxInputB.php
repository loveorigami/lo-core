<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use lo\widgets\Toggle;

/**
 * Class CheckBoxInput c виджетом Bootstrap toogle
 * Чекбокс
 * @package lo\core\inputs
 */
class CheckBoxInputB extends CheckBoxInput {

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index индекс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $this->widgetOptions, $options);

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()), [
            'template' => '{label} <div class="clearfix"></div>{input}{error}{hint}'
        ])->widget(Toggle::class, $options);
    }

}