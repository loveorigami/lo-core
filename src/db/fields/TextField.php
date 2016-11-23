<?php
namespace lo\core\db\fields;

use lo\core\db\ActiveQuery;

/**
 * Class TextField
 * Текстовое поле модели.
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TextField extends BaseField
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [$this->attr, 'filter', 'filter' => 'trim'];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    protected function search(ActiveQuery $query)
    {
        if ($this->model->hasAttribute($this->attr)) {
            $table = $this->model->tableName();
            $attr = $this->attr;
            $query->andFilterWhere(["like", "$table.$attr", preg_quote($this->model->{$this->attr})]);
        }
    }
}