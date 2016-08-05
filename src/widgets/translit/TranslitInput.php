<?php

namespace lo\core\widgets\translit;

use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Class TranslitInput
 * @package lo\core\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TranslitInput extends InputWidget
{
    /** @var string */
    public $generateFrom;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        TranslitInputAsset::register($this->view);

        $this->view->registerJs("

        ");
    }

    /**
     * @return string
     */
    public function run()
    {
        if ($this->hasModel()) {
            $html = Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $html = Html::textInput($this->name, $this->value, $this->options);
        }

        return $html;
    }
}