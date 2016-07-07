<?php

namespace lo\core\inputs;

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class HtmlInput
 * Html поле
 * @package lo\core\inputs
 */
class HtmlInput extends BaseInput
{
    const PRESET_MINI = 'mini';
    const PRESET_BASIC = 'basic';
    const PRESET_STANDART = 'standart';
    const PRESET_FULL = 'full';
    const PRESET_EXTRA = 'extra';

    /**
     * @var string контроллер файлового менеджера
     */
    public $fileManagerController = "elfinder/editor";

    /**
     * @var string папка
     */
    public $path;

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

        $ckeditorOptions = [];

        if ($this->path) {

            $editorOptions = [
                'preset' => self::PRESET_STANDART,
                'inline' => false,
                'allowedContent' => true,
                'autoParagraph' => false,
                'baseHref' => \Yii::getAlias('@storageUrl'),
            ];

            $fm = (is_array($this->fileManagerController)) ?
                $this->fileManagerController :
                [
                    $this->fileManagerController,
                    'path' => $this->path
                ];

            $ckeditorOptions = ElFinder::ckeditorOptions($fm, $editorOptions);

        }

        $widgetOptions = ArrayHelper::merge(
            ["editorOptions"=>$ckeditorOptions],
            $this->widgetOptions,
            ['options' => $options]
        );

        $attr = $this->modelField->attr;

        return $form->field($this->modelField->model, $this->getFormAttrName($index, $attr))->widget(CKEditor::class, $widgetOptions);

    }

} 