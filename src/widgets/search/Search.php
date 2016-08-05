<?php
namespace lo\core\widgets\search;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
use lo\core\widgets\block\Block;



class Search extends \lo\core\widgets\App
{

    public $title = '';
    public $action;
    public $searchModel;
    public $form_view = 'form';


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->startBlock();
    }
    /**
     * Renders the widget.
     */
    public function run()
    {
        echo "\n" . $this->renderBody();
        $this->endBlock();
    }
    /**
     * Renders the offcanvas body (if any).
     * @return string the rendering result
     */
    protected function renderBody()
    {
        return $this->render($this->form_view, [
            'searchModel' =>$this->searchModel,
            'action' => $this->action
        ]) . "\n";
    }

    protected function startBlock()
    {
        return Block::begin(['type'=>'pink', 'title'=>$this->title]);
    }

    protected function endBlock()
    {
        return  Block::end();
    }



}