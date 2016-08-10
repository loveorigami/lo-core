<?php

namespace lo\core\inputs;

use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * Class FileUploadInput
 * Поле ввода диапазона дат
 * @package lo\core\inputs
 * @property \lo\core\db\fields\FileUploadField $modelField
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
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $file = $this->getModel()->{$this->getAttr()};
echo $file;
        $initFile = [];
        $model = $this->modelField->model;
        if (is_file($this->modelField->storagePath.$file) && $model->scenario != $model::SCENARIO_INSERT) {
            $initFile = ArrayHelper::merge($this->defaultOptions, [
                'pluginOptions' => [
                    'initialPreview'=>[
                        //"http://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/FullMoon2010.jpg/631px-FullMoon2010.jpg"
                        $this->modelField->storageUrl. $file
                    ],
                    'overwriteInitial'=>true,
                    'initialPreviewAsData' => true,
                    /*                    'initialPreview' => [
                                            $this->modelField->storageUrl . $file
                                        ],

                                        'overwriteInitial' => true,*/
                    //'uploadUrl' => Url::to(['file-upload'])
                ]
            ]);
        }

        $options = ArrayHelper::merge($this->options, $options);
        $widgetOptions = ArrayHelper::merge($this->defaultOptions, $this->widgetOptions, ["options" => $options], $initFile);


        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(FileInput::class, $widgetOptions);
    }
}