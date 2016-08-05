<?php
namespace lo\core\db\fields;

/**
 * Class TimestampField
 * Поле метки времени
 * @package lo\core\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */

class TimestampField extends Field
{

    /**
     * @inheritdoc
     */
    public $showInForm = false;

    /**
     * @inheritdoc
     */

    public $showInTableInput = false;

    /**
     * @inheritdoc
     */
    public $showInGrid = false;

    /**
     * @inheritdoc
     */
    public $showInExtendedFilter = true;

    /**
     * @inheritdoc
     */
    protected function grid()
    {

        $grid = parent::grid();

        $grid["attribute"] = $grid["attribute"];

        $grid["format"] = "datetime";

        $grid['headerOptions'] = [
            'style' => 'width: 100px;',
        ];

        return $grid;

    }

    /**
     * @inheritdoc
     */
    protected function view()
    {

        $view = parent::view();

        $view["format"] = "datetime";

        return $view;

    }

}