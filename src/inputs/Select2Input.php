<?php

namespace lo\core\inputs;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class Select2Input
 * Выпадающий список, стилизированный виджетом Select2
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Select2Input extends DropDownInput
{

    public $options = [
        "multiple" => false,
        'encode' => false
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

        if (empty($data))
            return false;

        $options = ArrayHelper::merge($this->options, $options);

        $widgetOptions = ArrayHelper::merge([
            'theme' => Select2::THEME_KRAJEE,
            "data" => $data,
        ], $this->widgetOptions, ["options" => $options]);

        $attr = $this->getFormAttrName($index, $this->modelField->attr);

        return $form->field($this->modelField->model, $attr)->widget(Select2::class, $widgetOptions);
    }

} 