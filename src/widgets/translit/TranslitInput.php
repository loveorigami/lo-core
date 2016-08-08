<?php

namespace lo\core\widgets\translit;

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * Class TranslitInput
 * @package lo\core\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TranslitInput extends InputWidget
{
    /** @var string */
    public $generateTo;

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

        $from = $this->generateFrom;
        $to = $this->generateTo;

        $this->view->registerJs("
            function generateSlug()
            {
                var name = $('#$from').val();
                var old_slug = $('#$to').val();
                
                if(!old_slug){
                    new_slug = transliteration(name);
                    $('#$to').val(new_slug);
                }
                else{
                    alert('Поле не пустое!');
                }
               
            }
        ", View::POS_END);
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