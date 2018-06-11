<?php

namespace lo\core\inputs\map;

use lo\core\inputs\BaseInput;
use lo\widgets\gmap\SelectMapLocationWidget;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class YaMapInput
 * Поле выбора координат на яндекс карте
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class GoogleMapInput extends BaseInput
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
        $options = ArrayHelper::merge($this->options, $options);

        $widgetOptions = ArrayHelper::merge(["options" => ["class" => "form-control"]], $this->widgetOptions, ["options" => $options]);

        return $form->field($this->modelField->model, $this->getFormAttrName($index, $this->modelField->attr))->widget(SelectMapLocationWidget::class, $widgetOptions);
    }


} 