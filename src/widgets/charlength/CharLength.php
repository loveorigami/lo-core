<?php

namespace lo\core\widgets\charlength;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class CharLength extends InputWidget
{
    const INPUT_TEXT     = 'text';
    const INPUT_TEXTAREA = 'textarea';

    /**
     * @var string the type of input field to generate (default to 'text').
     */
    public $type = self::INPUT_TEXT;

    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * Initializes the widget.
     * This method will register the bootstrap asset bundle. If you override this method,
     * make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        CharLengthAsset::register($this->view);
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->registerClientScript();

        if ($this->hasModel()) {
            echo $this->type == self::INPUT_TEXTAREA ?
                Html::activeTextarea($this->model, $this->attribute, $this->options)
                : Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            echo $this->type == self::INPUT_TEXTAREA ?
                Html::textarea($this->name, $this->value, $this->options)
                : Html::textInput($this->name, $this->value, $this->options);
        }
    }

    /**
     * Generates and registers javascript to start the plugin.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $options = empty($this->clientOptions) ? "{}" : Json::encode($this->clientOptions);
        $js = "jQuery(\"#{$this->options['id']}\").charlength(" . $options . ")";
        $view->registerJs($js);
    }
}
