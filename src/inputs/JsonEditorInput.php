<?php

namespace lo\core\inputs;

use lo\widgets\Jsoneditor;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class AceEditorInput
 * ```php
 *  "text_intro" => [
 *      "definition" => [
 *          "class" => fields\HtmlField::class,
 *          "inputClass" => inputs\JsonEditorInput::class,
 *          'inputClassOptions'=>[
 *              "widgetOptions" => [
 *                  'editorOptions' => [
 *                      'modes' => ['code', 'form', 'text', 'tree', 'view'], // available modes
 *                      'mode' => 'tree', // current mode
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
 *
 * @package lo\core\inputs
 */
class JsonEditorInput extends BaseInput
{
    /**
     * Формирование Html кода поля для вывода в форме
     *
     * @param ActiveForm $form    объект форма
     * @param array      $options массив html атрибутов поля
     * @param bool|int   $index   индекс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $options);
        $widgetOptions = ArrayHelper::merge([
            'editorOptions' => [
                'modes' => ['code', 'form', 'text', 'tree', 'view'], // available modes
                'mode' => 'view',
            ],
        ],
            $this->widgetOptions,
            ['options' => $options]
        );

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(Jsoneditor::class, $widgetOptions);

    }

} 
