<?php

namespace lo\core\inputs;

use lo\core\widgets\TagsSorted;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class SortedTagsInput
 * Список тегов с возможностью сортировки
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TagsSortedInput extends BaseInput
{

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index индекс модели при табличном вводе
     * @return string
     */
    public $loadUrl=[];

    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        //$data = $this->modelField->getDataValue();

        $options = ArrayHelper::merge(["class" => "form-control"], $this->options, $options, ["multiple" => true]);

        $widgetOptions = ArrayHelper::merge([
            'loadUrl' => $this->loadUrl,
            'clientOptions' => [
                'plugins' => ['remove_button','drag_drop'],
                'valueField' => 'name',
                'labelField' => 'name',
                'searchField' => ['name'],
                'create' => false,
                'delimiter'=> ',',
                'persist'=> false,
            ],
        ], $this->widgetOptions, ["options" => $options]);

        $attr = $this->getFormAttrName($index, $this->modelField->attr);

        return $form->field($this->modelField->model, $attr)->widget(TagsSorted::className(), $widgetOptions);
    }

}