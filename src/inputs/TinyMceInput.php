<?php

namespace lo\core\inputs;

use milano\tinymce\TinyMce;
use milano\tinymce\fm\MihaildevElFinder;
use yii\widgets\ActiveForm;

/**
 * Class TinyMceInput
 * ```php
 *  "text_intro" => [
 *      "definition" => [
 *          "class" => fields\HtmlField::class,
 *          "inputClass" => [
 *              'class' => inputs\TinyMceInput::class,
 *              'path' => self::PATH,
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
class TinyMceInput extends CKEditorInput
{
    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index индекс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(TinyMce::class, [
            'fileManager' => [
                'class' => MihaildevElFinder::class,
                'controller' => $this->fileManagerController,
                'path' => $this->path
            ],
        ]);
    }
}