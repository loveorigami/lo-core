<?php

namespace lo\core\db\fields;

use Yii;

/**
 * Class TimestampField
 * Поле метки времени
 *
 * @package lo\core\db\fields
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
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
    protected function grid(): array
    {
        $grid = parent::grid();
        //$grid["format"] = "datetime";
        $grid['value'] = function ($model) {
            $attr = $this->attr;
            if (\extension_loaded('intl')) {
                return Yii::t('core', '{0, date, dd MMMM, YYYY HH:mm}', [$model->$attr]);
            }

            return date('Y-m-d G:i:s', $model->$attr);
        };
        $grid['headerOptions'] = [
            'style' => 'width: 120px;',
        ];

        return $grid;
    }

    /**
     * @inheritdoc
     */
    protected function view(): array
    {
        $view = parent::view();
        $view['format'] = 'datetime';

        return $view;
    }

}
