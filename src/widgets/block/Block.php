<?php
namespace lo\core\widgets\block;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Block renders
 *
 * ```php
 * echo Block::widget([
 *     'title' => 'Say hello...',
 * ]);
 * ```
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the Block widget:
 *
 * ```php
 * Block::begin([
 *     'color' => Block::TYPE_INFO,
 * ]);
 *
 * echo 'Say hello...';
 *
 * Block::end();
 * ```
 */
class Block extends Widget
{
    /**
     * Bootstrap Contextual Color Types
     */
    const TYPE_DEFAULT = 'default'; // use default
    const TYPE_PRIMARY = 'primary';
    const TYPE_INFO = 'info';
    const TYPE_DANGER = 'danger';
    const TYPE_WARNING = 'warning';
    const TYPE_SUCCESS = 'success';

    /** @var $type string Bootstrap Contextual Color Type default */
    public $type = self::TYPE_INFO;

    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * @var string
     */
    public $header;

    /**
     * Renders content
     * @var string
     */
    public $content;

    /** @var string $leftTools code of custom box toolbar in left corner - string html code */
    public $leftTools;

    /** @var string $leftTools code of custom box toolbar in right corner - string html code */
    public $rightTools;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->_initOptions();
        echo $this->startBlock();
    }


    /**
     * Renders the widget.
     */
    public function run()
    {
        echo "\n" . $this->renderContent();
        echo $this->endBlock();
    }

    /**
     * Initialize bootstrap Panel styling
     */
    private function _initOptions()
    {
        BlockAsset::register($this->view);
    }

    /**
     * @return string
     */
    protected function startBlock()
    {
        $div[] = Html::beginTag('div', ['class' => 'cboxl cboxl_' . $this->type]);
        $div[] = Html::tag('div', $this->renderHeader(), ['class' => 'cboxl_header cboxl_header_' . $this->type]);
        $div[] = Html::tag('div', '', ['class' => 'clearfix']);
        $div[] = Html::beginTag('div', ['class' => 'cboxl_content cboxl_content_' . $this->type]);
        return implode('', $div);
    }

    /**
     * Renders the offcanvas body (if any).
     * @return string the rendering result
     */
    protected function renderContent()
    {
        return $this->content . "\n";
    }

    /**
     * @return string
     */
    protected function endBlock()
    {
        $div[] = Html::endTag('div');
        $div[] = Html::endTag('div');
        return implode('', $div);
    }

    /**
     * @return null|string
     */
    private function renderHeader()
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
            return Html::tag('div', $left . Html::tag('span', $this->header) . $right);
        }
        return null;
    }

}