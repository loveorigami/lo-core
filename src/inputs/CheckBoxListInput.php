<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use lo\core\widgets\awcheckbox\AwesomeCheckbox;

/**
 * Class CheckBoxList
 * Список чекбоксов
 * ```php
 *  "groups" => [
 *      "definition" => [
 *          "class" => fields\ManyManyField::class,
 *          "inputClass" => inputs\CheckBoxListInput::class,
 *          "inputClassOptions" => [
 *              "options" => [
 *                  'isTree' => true
 *              ]
 *          ],
 *          "title" => Yii::t('backend', 'Groups'),
 *          "isRequired" => true,
 *          "showInGrid" => false,
 *          "data" => [$this, "getGroupsList"],
 *          "tab" => self::GROUP_TAB,
 *          "relationName" => "groups"
 *      ],
 *      "params" => [$this->owner, "group_ids"]
 *  ],
 * ```
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CheckBoxListInput extends BaseInput
{
    /**
     * Настройки по умолчанию
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
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $this->registerJs();
        $data = $this->modelField->getDataValue();

        if (empty($data)) {
            return false;
        }

        $options = ArrayHelper::merge(
            $this->defaultOptions,
            $this->widgetOptions,
            $options, ['list' => $data]
        );

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(AwesomeCheckbox::class, $options);
    }

    /**
     * Регистрация js
     */
    protected function registerJs()
    {
        $isTree = ArrayHelper::getValue($this->options, 'isTree');
        if ($isTree) {
            $id = Html::getInputId($this->getModel(), $this->getAttr());
            $js = <<<JS
        
        $('#$id').on('change', 'input[value="1"]', initFilters);
        
        function initFilters() {
            var filter = $(this).prop('checked');
            $('#$id input').prop('checked', filter);
        }
JS;
            $view = \Yii::$app->getView();
            $view->registerJs($js);
        }
    }
}