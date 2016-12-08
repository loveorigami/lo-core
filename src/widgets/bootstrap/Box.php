<?php

namespace lo\core\widgets\bootstrap;

use yii\bootstrap\ButtonGroup;
use yii\helpers\Html;

/**
 * Class Panel
 * @package lo\core\bootstrap\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 * ```php
 * <?= lo\core\widgets\bootstrap\Box::widget([
 *      'type'=> Box::TYPE_PRIMARY,
 *      'solid'=>true,
 *      'leftTools'=>'<button class="btn btn-success btn-xs create_button" ><i class="fa fa-plus-circle"></i> Добавить</button>',
 *      'tooltip'=>'Описание содержимого',
 *      'header'=>'Управление пользователями',
 *      'footer'=>'Всего '.User::counter().' активных пользователей',
 *      'collapse'=>true
 * ]); ?>
 * ```
 */
class Box extends BaseWidget
{
    /** @var boolean $solid is solid box header */
    public $solid = false;

    /** @var boolean $withBorder add border after box header (for AdminLte 2.0) */
    public $withBorder = true;

    /** @var string $tooltip box -tooltip */
    public $tooltip;

    /** @var string $tooltip_placement -top/bottom/left/or right */
    public $tooltip_placement = 'bottom';

    /**@var string $title */
    public $header;

    /** @var string $footer */
    public $footer = '';

    /** @var boolean $collapse show or not Box - collapse button */
    public $collapse = true;

    /** @var boolean $collapse_remember - set cookies for rememer collapse stage */
    public $collapse_remember = true;

    /** @var boolean $collapseDefault - show in collapsed mode inititally */
    public $collapseDefault = true;

    /** @var string|array $customTools code of custom box toolbar - string html code, or array for yii\bootstrap\ButtonGroup $buttons option */
    public $customTools;

    /** @var string|array $leftTools code of custom box toolbar in left corner - string html code, or array for yii\bootstrap\ButtonGroup $buttons option */
    public $leftTools;

    public $header_tag = 'h3';

    private $_cid = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->_cid = $this->options['id'] = 'bc_' . $this->getId();
        }
        $this->registerJs();
        Html::addCssClass($this->options, 'box');
        Html::addCssClass($this->options, 'box-' . $this->type);
        if ($this->solid) {
            Html::addCssClass($this->options, 'box-solid');
        }
        if ($this->collapse and $this->collapseDefault and !$this->collapse_remember) {
            Html::addCssClass($this->options, 'collapsed-box');
        }
        if (is_array($this->customTools)) {
            if ($this->collapse) {
                $this->customTools[] = '<button class="btn btn-box-tool" type="button" data-widget="collapse" id="' . $this->_cid . '_btn"><i class="collapsed fa fa-minus"></i></button>';
            }

            $this->customTools = ButtonGroup::widget(
                [
                    'buttons' => $this->customTools,
                    'encodeLabels' => false
                ]
            );
        } else {
            $this->customTools = $this->customTools . ($this->collapse ?
                    '<button class="btn btn-box-tool" type="button" data-widget="collapse" id="'
                    . $this->_cid . '_btn"><i class="collapsed fa fa-minus"></i></button>'
                    : '');
        }

        if (is_array($this->leftTools) && !empty($this->leftTools)) {
            $this->leftTools = ButtonGroup::widget(
                [
                    'buttons' => $this->leftTools,
                    'encodeLabels' => false
                ]
            );
        }

        $custTools = Html::tag('div', $this->customTools, ['class' => 'box-tools pull-right']);

        $headerContent = !$this->leftTools ? '' : Html::tag('div', $this->leftTools, ['class'=>'pull-left', 'style'=>'margin-right:5px;']);

        $headerContent .= (!$this->header ? '' : Html::tag($this->header_tag, $this->header, ['class' => 'box-title']));
        $headerContent .= ($this->customTools || $this->collapse) ? $custTools : '';

        $headerOptions = ['class' => 'box-header'];
        if ($this->withBorder) {
            Html::addCssClass($headerOptions, 'with-border');
        }

        if ($this->tooltip) {
            $headerOptions = array_merge(
                $headerOptions, [
                    'data-toggle' => 'tooltip',
                    'data-original-title' => $this->tooltip,
                    'data-placement' => $this->tooltip_placement
                ]
            );
        }

        $header = Html::tag('div', $headerContent, $headerOptions);

        echo '<div ' . Html::renderTagAttributes($this->options) . '>'
            . (!$this->header && !$this->collapse && !$this->customTools && !$this->leftTools
                ? ''
                : $header)
            . '<div class="box-body">';
    }

    public function run()
    {
        echo '</div>'
            . (!$this->footer ? '' : "<div class='box-footer'>" . $this->footer . "</div>")
            . '</div>';
    }

    public function registerJs()
    {
        if ($this->collapse_remember && $this->collapse) {
            $view = $this->getView();
            BoxAsset::register($view);
        }
    }
}
