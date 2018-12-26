<?php

namespace lo\core\inputs;

use Yii;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use lo\widgets\Toggle;

/**
 * Class CheckBoxInput c виджетом Bootstrap toggle
 * Чекбокс
 *
 * @package lo\core\inputs
 */
class CheckBoxInputB extends CheckBoxInput
{
    /**
     * Формирование Html кода поля для вывода в форме
     *
     * @param ActiveForm $form    объект форма
     * @param array      $options массив html атрибутов поля
     * @param bool|int   $index   индекс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false): string
    {
        $options = ArrayHelper::merge([
            'options' => [
                'label' => null,
                'inline' => true,
                'data-on' => Yii::t('common', 'Yes'),
                'data-off' => Yii::t('common', 'No'),
            ],
        ], $this->options, $this->widgetOptions, $options);

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()), [
            'template' => '{label} <div class="clearfix"></div>{input}{error}{hint}',
        ])->widget(Toggle::class, $options);
    }
}
