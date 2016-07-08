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
 *              'class'=>inputs\HtmlInput::class,
 *              'path' => 'objects/text', // folder for Elfinder
 *          ],
 *          "inputClassOptions" => [
 *              "widgetOptions"=>[
 *                  'editorOptions'=>[
 *                      'preset' => inputs\HtmlInput::PRESET_MINI,
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
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class HtmlField extends TextAreaField
{

    /**
     * @var $inputClass
     */
    public $inputClass = inputs\HtmlInput::class;

}