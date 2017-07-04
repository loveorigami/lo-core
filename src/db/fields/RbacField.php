<?php

namespace lo\core\db\fields;

use lo\core\db\ActiveQuery;
use lo\core\helpers\BaseUmodeHelper;
use lo\core\inputs\DropDownInput;
use yii\helpers\ArrayHelper;

/**
 * Class RbacField
 * Списочное поле модели. Поддерживает возможность создания зависимых списков.
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class RbacField extends BaseField
{
    /** @var bool значения выпадающего списка - числовые */
    public $numeric = false;

    /** @inheritdoc */
    public $inputClass = DropDownInput::class;


    /**
     * @inheritdoc
     */
    protected function defaultGridFilter()
    {
        return $this->getDataValue();
    }


    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();

        $grid["value"] = function ($user) {
            return BaseUmodeHelper::getRoleByUserId($user->id);
        };

        return $grid;

    }

    /**
     * @inheritdoc
     */
    protected function view()
    {
        $view = $this->defaultView();

        $value = $this->model->{$this->attr};

        if (is_string($value) OR is_int($value)) {
            $view["value"] = ArrayHelper::getValue($this->getDataValue(), $value, $value);
        }

        return $view;
    }

    /**
     * Поиск
     * @param ActiveQuery $query запрос
     */
    protected function search(ActiveQuery $query)
    {
        $table = $this->model->tableName();
        $role = $this->model->{$this->attr};
        if ($role) {
            $ids = BaseUmodeHelper::getUserIdsByRole($role);
            $query->andWhere(["$table.id" => $ids]);
        }
    }
}