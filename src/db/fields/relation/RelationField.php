<?php
namespace lo\core\db\fields\relation;

use lo\core\db\ActiveQuery;
use lo\core\db\fields\BaseField;

/**
 * Class RelationField
 * Базовый класс полей.
 * @package lo\core\db\fields\relation
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class RelationField extends BaseField
{
    const BEHAVIOR_PREF = 'rs';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        return $rules;
    }


    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();
        return $grid;
    }

    /**
     * @inheritdoc
     */
    protected function view()
    {
        $view = $this->defaultView();
        return $view;
    }

    /**
     * Поиск
     * @param ActiveQuery $query запрос
     */
    protected function search(ActiveQuery $query)
    {
        parent::search($query);
        if ($this->eagerLoading) {
            $query->with($this->relationName);
        }
    }
}