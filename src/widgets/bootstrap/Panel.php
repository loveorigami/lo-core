<?php

namespace lo\core\widgets\bootstrap;

use yii\helpers\Html;

/**
 * Class Panel
 * @package lo\core\bootstrap\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 * ```php
 * <?= lo\core\widgets\bootstrap\Panel::widget([
 *      'header' => true, // show header or false not showing
 *      'content' => '' // some content in body
 *      'footer' => false, // show footer or false not showing
 *      'type' => true, // get style for panel Panel::TYPE_DEFAULT  default
 * ]); ?>
 * ```
 */
class Panel extends BaseWidget
{
    /** @var $header bool showing header */
    public $header = true;

    /** @var $content mixed */
    public $content;

    /** @var $footer bool showing footer */
    public $footer = false;

    /** @var string $leftTools code of custom box toolbar in left corner - string html code */
    public $leftTools;

    /** @var string $leftTools code of custom box toolbar in right corner - string html code */
    public $rightTools;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_initOptions();
        echo Html::beginTag('div', $this->options);
        $this->_initHeader();
        echo Html::beginTag('div', ['class' => 'panel-body']);
        echo $this->content;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::endTag('div');
        $this->_initFooter();
        echo Html::endTag('div');
    }

    /**
     * Initialize bootstrap Panel styling
     */
    private function _initOptions()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        if (!isset($this->options['class'])) {
            $this->options['class'] = ' panel panel-' . $this->type;
        }

    }

    /**
     * Initialize Panel header
     */
    private function _initHeader()
    {
        if ($this->header) {

            $left = '';
            $right = '';

            if ($this->leftTools) {
                $left = Html::tag('div', $this->leftTools, ['class' => 'pull-left', 'style' => 'margin-right:5px;']);
            }
            if ($this->rightTools) {
                $right = Html::tag('div', $this->rightTools, ['class' => 'pull-right', 'style' => 'margin-left:5px;']);
            }
            echo Html::tag('div', $left . Html::tag('span', $this->header, ['class' => 'panel-title']) . $right, ['class' => 'panel-heading']);
        }


    }

    /**
     * Initialize Panel header
     */
    private function _initFooter()
    {
        if ($this->footer)
            Html::tag('div', $this->footer, ['class' => 'panel-footer']);
    }
}
