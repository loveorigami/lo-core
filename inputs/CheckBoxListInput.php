<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use lo\core\widgets\awcheckbox\AwesomeCheckbox;

/**
 * Class CheckBoxList
 * Список чекбоксов
 * ```php
 *  "groups" => [
 *      "definition" => [
 *          "class" => fields\ManyManyField::class,
 *          "inputClass" => inputs\CheckBoxListInput::class,
 *          "title" => Yii::t('backend', 'Groups'),
 *          "isRequired" => true,
 *          "showInGrid" => false,
 *          "data" => [$this, "getGroupsList"],
 *          "tab" => self::GROUP_TAB,
 *      ],
 *      "params" => [$this->owner, "group_ids", "groups"]
 *  ],
 * ```
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CheckBoxListInput extends DropDownInput
{
    /**
     * Настройки по умолчанию
     * @var array
     */
    public $options = [
        'type' => AwesomeCheckbox::TYPE_CHECKBOX,
        'style' => AwesomeCheckbox::STYLE_PRIMARY,
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
        $data = $this->modelField->getDataValue();

        if (empty($data))
            return false;

        $options = ArrayHelper::merge($this->options, $this->widgetOptions, $options, ['list' => $data]);

        $attr = $this->getFormAttrName($index, $this->modelField->attr);

        return $form->field($this->modelField->model, $attr)->widget(AwesomeCheckbox::class, $options);
    }
} 