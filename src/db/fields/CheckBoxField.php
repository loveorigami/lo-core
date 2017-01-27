<?php
namespace lo\core\db\fields;

use lo\core\grid\XEditableColumn;
use Yii;
use Yii\widgets\ActiveForm;
use lo\core\db\ActiveRecord;
use lo\core\inputs;

/**
 * Class CheckBoxField
 * Поле чекбокса для модели
 *      "status" => [
 *          "definition" => [
 *              "class" => fields\CheckBoxField::class,
 *              "title" => Yii::t('backend', 'Status'),
 *              "showInGrid" => false,
 *              "showInFilter" => true,
 *          ],
 *          "params" => [$this->owner, "status"]
 *      ],
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CheckBoxField extends BaseField
{
    /** @var $inputClass */
    public $inputClass = inputs\CheckBoxInput::class;

    /**
     * Конфигурация поля для грида (GridView)
     * @return array
     */
    protected function grid()
    {
        $grid = parent::grid();

        $grid['value'] = function ($model, $index, $widget) {
            return $model->{$this->attr} ?
                '<span class="btn btn-xs btn-success">' . Yii::t('core', 'Yes') . '</span>' :
                '<span class="btn btn-xs btn-default">' . Yii::t('core', 'No') . '</span>';

        };
        $grid['format'] = 'raw';
        $grid['headerOptions'] = [
            'style' => 'width: 60px;',
        ];

        return $grid;
    }

    /**
     * Конфигурация поля для детального просмотра
     * @return array
     */
    protected function view()
    {
        $view = parent::view();
        $view['value'] = ($this->model->{$this->attr}) ? Yii::t('core', 'Yes') : Yii::t('core', 'No');
        return $view;
    }

    /**
     * @inheritdoc
     */
    public function getExtendedFilterForm(ActiveForm $form, Array $options = [])
    {
        $data = $this->defaultGridFilter();

        if (!isset($options['prompt']))
            $options['prompt'] = '';

        return $form->field($this->model, $this->attr)->dropDownList($data, $options);
    }

    /**
     * @inheritdoc
     */
    protected function defaultGridFilter()
    {
        return [
            1 => Yii::t('core', 'Yes'),
            0 => Yii::t('core', 'No'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function xEditable()
    {
        return [
            'class' => XEditableColumn::class,
            'url' => $this->getEditableUrl(),
            'dataType' => 'select',
            'format' => 'raw',
            'editable' => ['source' => $this->defaultGridFilter()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [$this->attr, 'default', 'value' => 0, 'except' => ActiveRecord::SCENARIO_SEARCH];
        return $rules;
    }

}