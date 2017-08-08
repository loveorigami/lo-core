<?php

namespace lo\core\inputs;

use mihaildev\ckeditor\CKEditor;
use mihaildev\ckeditor\CKEditorPresets;
use mihaildev\elfinder\ElFinder;
use Yii;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class CKEditorInput
 * ```php
 *  "text_intro" => [
 *      "definition" => [
 *          "class" => fields\HtmlField::class,
 *          "inputClass" => [
 *              'class' => inputs\CKEditorInput::class,
 *              'path' => self::PATH,
 *          ],
 *          'inputClassOptions'=>[
 *              "widgetOptions" => [
 *                  'editorOptions' => [
 *                      'height' => 100 // высота редактора
 *                      'preset' => self::PRESET_FULL,
 *                  ],
 *              ],
 *          ],
 *          "title" => Yii::t('backend', 'Text intro'),
 *          "showInExtendedFilter" => false,
 *          "showInGrid" => false,
 *          "tab" => self::TEXT_TAB,
 *      ],
 *      "params" => [$this->owner, "text_intro"]
 *  ],
 * ```
 * @package lo\core\inputs
 */
class CKEditorInput extends BaseInput
{
    const PRESET_MINI = CKEditorPresets::MINI;
    const PRESET_BASIC = CKEditorPresets::BASIC;
    const PRESET_STANDART = CKEditorPresets::STANDART;
    const PRESET_FULL = CKEditorPresets::FULL;
    const PRESET_EXTRA = CKEditorPresets::EXTRA;

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
     * @param bool|int $index индекс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $options);

        $ckeditorOptions = [];

        if ($this->path) {

            $editorOptions = [
                'preset' => self::PRESET_FULL,
                'inline' => false,
                'allowedContent' => true,
                'autoParagraph' => false,
                'baseHref' => Yii::getAlias('@storageUrl'),
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
            ["editorOptions" => $ckeditorOptions],
            $this->widgetOptions,
            ['options' => $options]
        );

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(CKEditor::class, $widgetOptions);

    }

} 