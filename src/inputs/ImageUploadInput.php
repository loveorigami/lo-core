<?php

namespace lo\core\inputs;

use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * Class ImageUploadInput
 * Поле ввода диапазона дат
 * @package lo\core\inputs
 * @property \lo\core\db\fields\ImageUploadField $modelField
 */
class ImageUploadInput extends BaseInput
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
        ],
        'options' => ['accept' => 'image/*']
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
        $initFile = [];
        $model = $this->getModel();
        $file = $model->getThumbUploadUrl($this->getAttr(), 'thumb');
        echo $file;

        if ($file && $model->scenario != $model::SCENARIO_INSERT) {
            $initFile = ArrayHelper::merge($this->defaultOptions, [
                'pluginOptions' => [
                    'initialPreview'=>[
                        $file
                    ],
                    'overwriteInitial'=>true,
                    'initialPreviewAsData' => true,
                ]
            ]);
        }

        $options = ArrayHelper::merge($this->options, $options);

        $widgetOptions = ArrayHelper::merge(
            $this->defaultOptions,
            $this->widgetOptions,
            ["options" => $options],
            $initFile
        );

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(FileInput::class, $widgetOptions);
    }
}