<?php
namespace lo\core\widgets\offcanvas;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\InvalidConfigException;


/**
 * OffCanvas renders an OffCanvas component.
 *
 * For example,
 *
 * ```php
 * echo OffCanvas::widget([
 *     'toggleButton' => [
 *         'label' => 'Click me',
 *          'tag' => 'a',
 *     ],
 *     'body' => 'Say hello...',
 * ]);
 * ```
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the OffCanvas menu:
 *
 * ```php
 * OffCanvas::begin([
 *     'toggleButton' => [
 *          'label' => 'Click me',
 *          'tag' => 'a',
 *      ]
 * ]);
 *
 * echo 'Say hello...';
 *
 * OffCanvas::end();
 * ```
 */

class OffCanvas extends \lo\core\widgets\App
{

    public $list = '';

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
     * @var array the options for rendering the toggle button tag.
     * The close button is displayed in the header of the modal window. Clicking
     * on the button will hide the modal window. If this is null, no close button will be rendered.
     *
     * The following special options are supported:
     *
     * - tag: string, the tag name of the button. Defaults to 'button'.
     * - label: string, the label of the button. Defaults to none.
     * - href: string
     *
     * The rest of the options will be rendered as the HTML attributes of the button tag.
     * Please refer to the [OffCanvas documentation](http://www.getuikit.com/docs/offcanvas.html)
     * for the supported HTML attributes.
     */
    public $toggleButton = [
        'label' => '<span class="fa fa-bars"></span>',
        'class' => 'btn btn-primary visible-xs visible-sm pull-left',
        'data-toggle' => 'offcanvas',
    ];

    /**
     * @var array the HTML attributes of the inner bar
     */
    public $options = [
        'class' =>'col-lg-3 col-md-3 col-sm-12 col-xs-12 sidebar-offcanvas'
    ];
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        OffCanvasAsset::register($this->view);

        echo $this->renderToggleButton() . "\n";
        echo Html::beginTag('div', $this->options) . "\n";
    }
    /**
     * Renders the widget.
     */
    public function run()
    {
        echo "\n" . $this->renderBody();
        echo "\n" . Html::endTag('div');
    }
    /**
     * Renders the offcanvas body (if any).
     * @return string the rendering result
     */
    protected function renderBody()
    {
        return $this->body . "\n";
    }
    /**
     * Renders the toggle button.
     * @return string the rendering result
     */
    protected function renderToggleButton()
    {
        if ($this->toggleButton !== null) {
            $tag = ArrayHelper::remove($this->toggleButton, 'tag', 'button');
            $label = ArrayHelper::remove($this->toggleButton, 'label', 'Show');

            if ($tag === 'button' && !isset($this->toggleButton['type'])) {
                $this->toggleButton['type'] = 'button';
            }
            if ($tag === 'a' && !isset($this->toggleButton['href'])) {
                $this->toggleButton['href'] = '#' . $this->options['id'];
            }
            return Html::tag($tag, $label, $this->toggleButton);
        } else {
            return null;
        }
    }


}