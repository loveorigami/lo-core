<?php

namespace lo\core\inputs;

use lo\core\db\fields\SlugField;
use lo\core\widgets\translit\TranslitInput;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class SlugInput
 * Текстовое поле
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 * @property SlugField $modelField
 */
class SlugInput extends BaseInput
{
    const TRANSLIT_FROM_LANG = 'ru';

    public $lang = self::TRANSLIT_FROM_LANG;

protected $tpl = [
    'template' => '{label}
                    <div class="input-group">{input}
                        <div class="input-group-btn">
                                <a href="#" data-toggle="modal" data-target="#add-" class="btn btn-primary">
                                    <i class="fa fa-plus"></i>
                                </a>
                        </div>
                    </div>
                    {error}{hint}',
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
        $options = ArrayHelper::merge($this->options, $options);

        $widgetOptions = ArrayHelper::merge(
            [
                "options" => ["class" => "form-control"],
                'generateFrom' => $this->modelField->generateFrom
            ],
            $this->widgetOptions,
            ["options" => $options]
        );

        return $form->field($this->modelField->model, $this->getFormAttrName($index, $this->modelField->attr), $this->tpl)->widget(TranslitInput::class, $widgetOptions);

    }
} 