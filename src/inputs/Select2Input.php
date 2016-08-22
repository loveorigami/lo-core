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
    const THEME = Select2::THEME_KRAJEE;
    public $options = [
        "multiple" => false,
        'encode' => false
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
        $data = $this->modelField->getDataValue();

        if (empty($data)){
            return false;
        }

        $options = ArrayHelper::merge($this->options, $options);

        $widgetOptions = ArrayHelper::merge([
            'theme' => self::THEME,
            "data" => $data,
        ], $this->widgetOptions, ["options" => $options]);

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()), $this->fieldTemplate)->widget(Select2::class, $widgetOptions);
    }

}