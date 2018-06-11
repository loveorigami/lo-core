<?php

namespace lo\core\widgets;

use yii\web\View;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\base\InvalidConfigException;

/**
 * Class DependDropDown
 * Виджет для организации зависимых списков
 * @package lo\core\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class DependDropDown extends InputWidget
{
    /** @var array маршрут для подгрузки зависимого списка */
    public $loadUrl;

    /** @var string имя зависимого атрибута модели. используется если в виджет передана модель */
    public $dependAttr;

    /** @var string jQuery селектор зависимого списка. используется если модель отсутствует */
    public $dependSelector;

    /** @var bool генерировать событие change на элементе при его загрузке в структуру документа */
    public $triggerChange = false;

    /** @var array массив значений зависмого списка ($key=>$value) */
    public $data = [];

    /** @var string имя атрибута передаваемого на сервер */
    public $serverAttr = "id";

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->dependAttr) AND empty($this->dependSelector))
            return;

        if ($this->hasModel() AND empty($this->dependSelector)) {
            $dependSelector = "#" . Html::getInputId($this->model, $this->dependAttr);
        } else {
            $dependSelector = $this->dependSelector;
        }

        $url = Url::toRoute($this->loadUrl);

        $id = $this->options['id'];

        $this->view->registerJs("
			$('#{$id}').on('change', function(){

				var val = $(this).val();
				$(this).prev().val(val);
				var inp = $('$dependSelector');
				var hidden = inp.prev();

				if(!val) {
					inp.html('');
					hidden.val('');
					inp.trigger('change').attr('disabled', true);
					return;
				}

				inp.attr('disabled', false),

				$.get('$url', {'{$this->serverAttr}': val}, function(data){
					inp.html(data);
					inp.val(hidden.val()).trigger('change');
				});
			});
		", View::POS_END);

        if ($this->triggerChange) {
            $this->view->registerJs("
				$('#{$id}').trigger('change');
			", View::POS_READY);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            $html = Html::activeHiddenInput($this->model, $this->attribute, ["id" => null]);
            $html .= Html::activeDropDownList($this->model, $this->attribute, $this->data, $this->options);
        } else {
            $html = Html::hiddenInput($this->name, $this->value, ["id" => null]);
            $html .= Html::dropDownList($this->name, $this->value, $this->options);
        }
        return $html;
    }
}