<?php
namespace lo\core\widgets\block;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\InvalidConfigException;


/**
 * Block renders an OffCanvas component.
 *
 * For example,
 *
 * ```php
 * echo Block::widget([
 *     'title' => 'Say hello...',
 * ]);
 * ```
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the Block menu:
 *
 * ```php
 * Block::begin([
 *     'color' => 'pink',
 * ]);
 *
 * echo 'Say hello...';
 *
 * Block::end();
 * ```
 */

class Block extends \lo\core\widgets\App
{

    public $title = '';
    public $type = 'cyan';

    /**
     * Renders the widget.
     */
    /**
     * @var string the body content in the OffCanvas component. Note that anything between
     * the [[begin()]] and [[end()]] calls of the OffCanvas widget will also be treated
     * as the body content, and will be rendered before this.
     */
    public $body;


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        BlockAsset::register($this->view);
        echo $this->startBlock();
    }
    /**
     * Renders the widget.
     */
    public function run()
    {
        echo "\n" . $this->renderBody();
        echo $this->endBlock();
    }
    /**
     * Renders the offcanvas body (if any).
     * @return string the rendering result
     */
    protected function renderBody()
    {
        return $this->body . "\n";
    }

    protected function startBlock()
    {
        $str='
            <div class="box box_'.$this->type.'_s"></div>
            <div class="box2 box_'.$this->type.'"></div>
            <div class="box_into box_'.$this->type.'">
                <div class="title" align="center">'.$this->title.'</div>
            </div>
            <div class="box3 box_'.$this->type.'"></div>
            <div class="box6 box_'.$this->type.'2_s">
                <div class="box4 box_'.$this->type.'2"></div>
            </div>
            <div class="box6 box_'.$this->type.'3_s">
            <div class="box5 box_'.$this->type.'3">';

        return $str;

    }

    protected function endBlock()
    {
        $str='
		</div>
		</div>
		<div class="box6 box_'.$this->type.'2_s">
			<div class="box4 box_'.$this->type.'2"></div>
		</div>
		<div class="box3 box_'.$this->type.'"></div>
		<div class="box2 box_'.$this->type.'"></div>
		<div class="box box_'.$this->type.'_s"></div>
		<div class="clear_small"></div>';

        return $str;

    }



}