<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use lo\core\widgets\MultipleInputWidget;

/**
 * Class MultiInput
 * Поле ввода нескольких значений
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class MultiInput extends BaseInput
{
    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index индекс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge(
            $this->widgetOptions,
            $this->options,
            $options
        );

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(MultipleInputWidget::class, $options);
    }
} 