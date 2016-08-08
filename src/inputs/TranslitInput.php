<?php

namespace lo\core\inputs;

use lo\core\db\fields\SlugField;
use lo\core\widgets\translit\TranslitInput as TranslitWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * Class TranslitInput
 * Текстовое поле
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 * @property SlugField $modelField
 */
class TranslitInput extends BaseInput
{
    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $options);

        $attr = $this->getFormAttrName($index, $this->getAttr());

        $fieldTemplate = [
            'template' => '{label}
                    <div class="input-group">{input}
                        <div class="input-group-btn">
                                <a href="#"  class="btn btn-primary" onclick="generateSlug(); return false;">
                                    <i class="fa fa-exchange"></i>
                                </a>
                        </div>
                    </div>
                    {error}{hint}',
        ];

        $widgetOptions = ArrayHelper::merge(
            [
                "options" => ["class" => "form-control"],
                'generateFrom' => Html::getInputId($this->getModel(), $this->modelField->generateFrom),
                'generateTo' => Html::getInputId($this->getModel(), $attr),
            ],
            $this->widgetOptions,
            ["options" => $options]
        );

        return $form->field($this->getModel(), $attr, $fieldTemplate)->widget(TranslitWidget::class, $widgetOptions);

    }
} 