<?php

namespace lo\core\db\fields;
use lo\core\inputs;

/**
 * Class HtmlField
 * Поле WYSIWYG редактора. Использует CKEditor
 *  "text" => [
 *      "definition" => [
 *          "class" => fields\HtmlField::class,
 *          "inputClass" =>[
 *              'class'=>inputs\CKEditorInput::class,
 *              'path' => 'objects/text', // folder for Elfinder
 *          ],
 *          "inputClassOptions" => [
 *              "widgetOptions"=>[
 *                  'editorOptions'=>[
 *                      'preset' => inputs\CKEditorInput::PRESET_MINI,
 *                  ]
 *              ],
 *          ],
 *          "title" => Yii::t('backend', 'Text'),
 *          "showInGrid" => false,
 *          "isRequired" => true,
 *          "tab" => self::TEXT_TAB,
 *      ],
 *          "params" => [$this->owner, "text"]
 *  ],
 * @package lo\core\db\fields
 */
class HtmlField extends TextAreaField
{
    /**
     * @var $inputClass
     */
    public $inputClass = inputs\CKEditorInput::class;

}