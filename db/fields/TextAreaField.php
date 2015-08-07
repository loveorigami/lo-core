<?php
namespace lo\core\db\fields;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class TextAreaField
 * Поле текстовой области модели
 * @package lo\core\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class TextAreaField extends TextField
{

    /**
     * @var bool отображать в гриде
     */
    public $showInGrid = false;

    /**
     * @var bool отображать в фильтре грида
     */
    public $showInFilter = false;

    /**
     * @var bool отображать в расширенном фильре
     */
    public $showInExtendedFilter = false;

    /**
     * @inheritdoc
     */
    public $inputClass = "\lo\core\inputs\TextAreaInput";

    /**
     * @inheritdoc
     */
    public $formTemplate = '<div class="co-xs-12 col-lg-12">{input}</div>';

    /**
     * @inheritdoc
     */
    protected function view()
    {

        $view = parent::view();

        $view['format'] = 'html';

        return $view;

    }

}