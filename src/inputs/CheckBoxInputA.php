<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use lo\core\widgets\awcheckbox\AwesomeCheckbox;

/**
 * Class CheckBoxInputA
 *  "status" => [
 *      "definition" => [
 *          "class" => fields\CheckBoxField::class,
 *          "inputClass" => inputs\CheckBoxInputA::class,
 *          "inputClassOptions" => [
 *              'widgetOptions'=>[
 *                  'style' => 'danger'
 *              ],
 *          ],
 *          "title" => Yii::t('backend', 'Status'),
 *          "showInGrid" => false,
 *          "showInFilter" => true,
 *      ],
 *      "params" => [$this->owner, "status"]
 *  ],
 * Чекбокс c виджетом Awesome Bootstrap Checkbox
 * @package lo\core\inputs
 */
class CheckBoxInputA extends CheckBoxInput
{
    /**
     * Опции по умолчанию
     * @var array
     */
    protected $defaultOptions = [
        'type' => AwesomeCheckbox::TYPE_CHECKBOX,
        'style' => AwesomeCheckbox::STYLE_PRIMARY,
    ];

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index индекс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false): string
    {

        $options = ArrayHelper::merge(
            $this->options,
            $options,
            $this->defaultOptions,
            $this->widgetOptions
        );

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()), [
            'template' => '{input}{error}{hint}'
        ])->widget(AwesomeCheckbox::class, $options);
    }

}