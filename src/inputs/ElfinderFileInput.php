<?php

namespace lo\core\inputs;

use mihaildev\elfinder\InputFile;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class ElfinderFileInput
 * @package lo\core\inputs
 */
class ElfinderFileInput extends BaseInput
{
    /** @var string контроллер файлового менеджера */
    public $fileManagerController = "elfinder/path";

    /**
     * Опции по умолчанию
     * @var array
     */
    protected $defaultOptions = [
        "template" => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
        "options" => [
            "class" => "form-control"
        ],
        "buttonOptions" => [
            "class" => "btn btn-info"
        ],
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
        $options = ArrayHelper::merge($this->options, $options);

        $widgetOptions = ArrayHelper::merge(
            ['controller' => $this->fileManagerController],
            $this->defaultOptions,
            $this->widgetOptions,
            ['options' => $options]
        );

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(InputFile::class, $widgetOptions);
    }
}