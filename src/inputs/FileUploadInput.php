<?php

namespace lo\core\inputs;

use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class FileUploadInput
 * Поле ввода диапазона дат
 * @package lo\core\inputs
 */
class FileUploadInput extends BaseInput
{
    /**
     * Опции по умолчанию
     * @var array
     */
    protected $defaultOptions = [
        'language' => 'ru',
        'pluginOptions' => [
            'showRemove' => false,
            'showUpload' => false,
        ]
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
        $options = ArrayHelper::merge($this->options, $options);
        $widgetOptions = ArrayHelper::merge($this->defaultOptions, $this->widgetOptions, ["options" => $options]);

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(FileInput::class, $widgetOptions);
    }
}