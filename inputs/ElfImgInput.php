<?php

namespace lo\core\inputs;

use mihaildev\elfinder\InputFile;
use yii\helpers\ArrayHelper;
use lo\core\helpers\FileHelper;
use yii\widgets\ActiveForm;

/**
 * Class HtmlInput
 * Html поле
 * @package lo\core\inputs
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class ElfImgInput extends ElfFileInput {

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

        $widgetOptions = ArrayHelper::merge([
            "template"=>'<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
            //'path' => 'import',
            'controller' => $this->fileManagerController,
            "options" => [
                "class" => "form-control"
            ],
            "buttonOptions" => [
                "class" => "btn btn-info"
            ],
        ], $this->widgetOptions, ['options'=> $options]);

        $attr = $this->modelField->attr;
        $img = $this->modelField->model[$attr];

        $frm = '';

        $frm.= $form->field($this->modelField->model, $this->getFormAttrName($index, $attr))->widget(InputFile::className(), $widgetOptions);
        $frm.= ($img) ? FileHelper::storageImg($img, ['width'=>$this->modelField->viewWidth]) : '';
        return $frm;

    }


} 