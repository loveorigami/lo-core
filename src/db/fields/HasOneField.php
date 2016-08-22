<?php
namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;
use lo\core\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class HasOneField
 * Поле для связей Has One. Интерфейс привязки в форме в виде выпадающего списка.
 *
 *  public function getCats()
 *  {
 *      $models = Category::find()->published()->orderBy(["name" => SORT_ASC])->all();
 *      return ArrayHelper::map($models, "id", "name");
 *  }
 *
 *  "cat_id" => [
 *      "definition" => [
 *          "class" => fields\HasOneField::class,
 *          "title" => Yii::t('backend', 'Category'),
 *          "isRequired" => false,
 *          "data" => [$this, "getCats"], // массив всех категорий (см. выше)
 *          "eagerLoading" => true,
 *          "numeric" => false,
 *          "showInGrid" => false,
 *      ],
 *      "params" => [$this->owner, "cat_id", "cat"] // id и relation getCat
 *  ],
 *
 * @package lo\core\db\fields
 */
class HasOneField extends ListField
{
    /** @var string имя атрибута связанной модели отображаемого в гриде */
    public $gridAttr = "name";

    /** @var boolean */
    public $numeric = true;

    /** @var boolean проверить наличие связанной модели */
    public $checkExist = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        if ($this->checkExist) {
            $relation = $this->model->getRelation($this->relationName);
            $rules[] = [$this->attr, 'exist', 'isEmpty' => [$this, "isEmpty"], 'targetClass' => $relation->modelClass, 'targetAttribute' => key($relation->link), 'except' => [ActiveRecord::SCENARIO_SEARCH]];
        }
        return $rules;
    }

    public function isEmpty($v)
    {
        return empty($v);
    }

    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();
        $grid["value"] = function ($model, $index, $widget) {
            return ArrayHelper::getValue($model, "{$this->relationName}.{$this->gridAttr}");
        };

        return $grid;
    }

    /**
     * @inheritdoc
     */
    protected function view()
    {
        $view = $this->defaultView();
        $view["value"] = ArrayHelper::getValue($this->model, "{$this->relationName}.{$this->gridAttr}");
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