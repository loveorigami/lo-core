<?php

namespace lo\core\inputs;

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\rating\StarRating;

/**
 * Class RatingInput
 * Поле рейтинга
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class RatingInput extends BaseInput
{
    /**
     * Опции по умолчанию
     * @var array
     */
    protected $defaultOptions = [
        'language' => 'ru',
        'pluginOptions' => [
            'theme' => 'krajee-fa',
            'filledStar' => '&#x2605;',
            'emptyStar' => '&#x2606;',
            'size' => 'xs',
        ]
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
		$options = ArrayHelper::merge($this->options, $options);

		$widgetOptions = ArrayHelper::merge($this->defaultOptions, $this->widgetOptions, ["options"=>$options]);

		return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(StarRating::class, $widgetOptions);
	}

}