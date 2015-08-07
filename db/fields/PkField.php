<?php
namespace lo\core\db\fields;

/**
 * Class PkField
 * Поле первичного ключа
 * @package lo\core\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class PkField extends Field
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

    public function rules()
    {

        $rules = [[$this->attr, 'safe', 'on' => \lo\core\db\ActiveRecord::SCENARIO_SEARCH]];

        return $rules;

    }

    /**
     * Конфигурация поля для грида (GridView)
     * @return array
     */
    protected function grid()
    {

        $grid = parent::grid();

        $grid['headerOptions'] = [
            'style' => 'width: 50px;',
        ];

        return $grid;

    }
}