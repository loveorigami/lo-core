<?php
namespace lo\core\db\fields\relation;

use lo\core\behaviors\SaveRelations;
use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;
use lo\core\db\fields\BaseField;
use yii\helpers\ArrayHelper;

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
     * @return array
     */
    public function behaviors()
    {
        $parent = parent::behaviors();

        if(
            !empty($this->relationName) AND
            !empty($this->relationAttr) AND
            $this->model->scenario != ActiveRecord::SCENARIO_SEARCH
        ) {
            $code = self::BEHAVIOR_PREF . ucfirst($this->relationName);
            $behavior[$code] = [
                'class' => SaveRelations::class,
            ];
            $parent = ArrayHelper::merge($parent, $behavior);
        }

        return $parent;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
            $rules[] = [$this->relationName, 'safe'];
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