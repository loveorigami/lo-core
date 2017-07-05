<?php

namespace lo\core\db\fields;

use lo\core\widgets\DatePicker;
use Yii;

/**
 * Class TimestampField
 * Поле метки времени
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TimestampField extends BaseField
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
        //$grid["format"] = "datetime";
        $grid["value"] = function ($model) {
            $attr = $this->attr;
            if (extension_loaded('intl')) {
                return Yii::t('core', '{0, date, MMMM dd, YYYY HH:mm}', [$model->$attr]);
            } else {
                return date('Y-m-d G:i:s', $model->$attr);
            }
        };
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