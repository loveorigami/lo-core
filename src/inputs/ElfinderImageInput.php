<?php

namespace lo\core\inputs;

use mihaildev\elfinder\InputFile;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class ElfinderImageInput
 * Html поле
 * @package lo\core\inputs
 */
class ElfinderImageInput extends ElfinderFileInput {

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $options);

        $widgetOptions = ArrayHelper::merge(
            ['controller' => $this->fileManagerController],
            $this->defaultOptions,
            $this->widgetOptions,
            ['options' => $options]
        );

        //$attr = $this->modelField->attr;
        ///$img = $this->modelField->model[$attr];
        //$frm = '';
        //$frm.= ($img) ? FileHelper::storageImg($img, ['width'=>$this->modelField->viewWidth]) : '';

        $frm = $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(InputFile::class, $widgetOptions);

        return $frm;
    }
} 