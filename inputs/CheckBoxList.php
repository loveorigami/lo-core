<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use lo\core\widgets\awcheckbox\AwesomeCheckbox;


/**
 * Class CheckBoxList
 * Выпадающий список
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CheckBoxList extends DropDownInput {

    public $options = [
        'type' => AwesomeCheckbox::TYPE_CHECKBOX,
        'style' => AwesomeCheckbox::STYLE_PRIMARY,
    ];
    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */

    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $data = $this->modelField->getDataValue();

        if(empty($data))
            return false;

        $options = ArrayHelper::merge($this->options, $this->widgetOptions, $options, ['list'=>$data]);

        $attr = $this->getFormAttrName($index, $this->modelField->attr);

        return $form->field($this->modelField->model, $attr)->widget(AwesomeCheckbox::class, $options);
    }


} 