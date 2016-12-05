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
        $view = $this->getView();
        BaseAsset::register($view);
    }

    /**
     * Initialize Panel header
     */
    private function _initHeader()
    {
        if ($this->header)
            echo Html::tag('div', Html::tag('h3', $this->header, ['class' => 'panel-title']), ['class' => 'panel-heading']);
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
