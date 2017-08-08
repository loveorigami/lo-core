<?php

namespace lo\core\inputs;

use trntv\aceeditor\AceEditor;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class AceEditorInput
 * ```php
 *  "text_intro" => [
 *      "definition" => [
 *          "class" => fields\HtmlField::class,
 *          "inputClass" => inputs\AceEditorInput::class,
 *          'inputClassOptions'=>[
 *              "widgetOptions" => [
 *                  'mode'=>'html',
 *                  'theme'=>'github',
 *                  'readOnly'=>'true'
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
class AceEditorInput extends BaseInput
{
    const MODE_HTML = 'html';
    const THEME_GITHUB = 'github';
    const THEME_CHROME = 'chrome';

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
        $widgetOptions = ArrayHelper::merge(
            $this->widgetOptions,
            ['options' => $options]
        );

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(AceEditor::class, $widgetOptions);

    }

} 