<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use lo\core\widgets\yamap\YaMapInput as Widget;
use yii\widgets\ActiveForm;

/**
 * Class YaMapInput
 * Поле выбора координат на яндекс карте
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class YaMapInput extends BaseInput
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

        return $form->field($this->modelField->model, $this->getFormAttrName($index, $this->modelField->attr),
            [
               'template' => '{label}
                                <div class="input-group">{input}
                                    <div class="input-group-btn">
                                        <div class="btn btn-primary btn-map">
                                            <i class="fa fa-map-marker"></i>
                                        </div>
                                    </div>
                                </div>
                                {error}{hint}',
            ]
        )->widget(Widget::className(), $widgetOptions);
    }


} 