<?php

namespace lo\core\widgets\bootstrap;

use \yii\bootstrap\Widget;

/**
 * Class BaseWidget
 * @package lo\core\bootstrap\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class BaseWidget extends Widget
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
    public $type = 'default';

    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
}
