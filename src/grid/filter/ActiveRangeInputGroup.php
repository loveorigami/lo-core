<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 31.05.2018
 * Time: 11:49
 */

namespace lo\core\grid\filter;

use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;

class ActiveRangeInputGroup extends Widget
{
    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;

    public $attributeFrom;

    public $attributeTo;

    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $inputOptions = [
        'class' => 'form-control',
        'style' => 'width: 50%;',
    ];

    public $options = [];

    public function run()
    {
        return Html::tag('div',
            Html::activeTextInput($this->model, $this->attributeFrom, $this->inputOptions) .
            Html::activeTextInput($this->model, $this->attributeTo, $this->inputOptions),
            array_merge(['class' => 'input-group'], $this->options));

    }
}