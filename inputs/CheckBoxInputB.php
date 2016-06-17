<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use lo\widgets\Toggle;

/**
 * Class CheckBoxInput
 * Чекбокс
 * @package lo\core\inputs
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class CheckBoxInputB extends BaseInput {

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $this->widgetOptions, $options);

        return $form->field($this->modelField->model, $this->getFormAttrName($index, $this->modelField->attr), [
            'template' => '{label} <div class="clearfix"></div>{input}{error}{hint}'
        ])->widget(Toggle::class, $options);
    }

}