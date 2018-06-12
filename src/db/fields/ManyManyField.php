<?php
namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;
use lo\core\db\ActiveQuery;
use lo\core\inputs\Select2MultiInput;
use yii\helpers\ArrayHelper;

/**
 * Class ManyManyField
 * Поле для связей Many Many
 * @package lo\core\db\fields
 *
 * ```php
 *  "categories" => [
 *      "definition" => [
 *          "class" => fields\ManyManyField::class,
 *          "title" => Yii::t('backend', 'categories'),
 *          "isRequired" => true,
 *          "showInGrid" => false,
 *          "data" => [$this, "getСategoriesList"],
 *          "relationName" => "categories" // relation getCategories()
 *      ],
 *      "params" => [$this->owner, "categoriesIds"]
 *  ],
 * ```
 *
 * Параметр data получаем из отдельного метода
 * ```php
 *  public function getСategoriesList()
 *  {
 *      $models = Сategories::find()->published()->orderBy(["name" => SORT_ASC])->asArray()->all();
 *      return ArrayHelper::map($models, "id", "name");
 *  }
 * ```
 *
 */
class ManyManyField extends HasOneField
{
    /** @var bool Жадная загрузка */
    public $eagerLoading = true;

    /** @var bool Проверку на целое число пропускаем, т.к. работаем с массивом */
    public $numeric = false;

    /** @var bool */
    public $checkExist = false;

    /** @var string Класс обработчик по умолчанию */
    public $inputClass = Select2MultiInput::class;

    /** @var string|array имя класса, либо конфигурация компонента который рендерит поле ввода расширенного фильтра */
    public $filterInputClass = Select2MultiInput::class;

    /**
     * Отображение в гриде
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();
        $grid["value"] = function ($model, $index, $widget) {
            return $this->getStringValue($model);
        };
        //$grid["class"] = Select2Column::class;
        return $grid;
    }

    /**
     * Возвращает строковое представление связанных моделей для отображения в гриде и при детальном просмотре
     * @param ActiveRecord $model
     * @return string
     */
    protected function getStringValue($model)
    {
        $relatedAll = $model->{$this->relationName};
        $arr = [];

        foreach ($relatedAll AS $related) {
            $arr[] = ArrayHelper::getValue($related, $this->gridAttr);
        }

        return implode(", ", $arr);
    }

    /**
     * Отображение при детальном просмотре
     */
    protected function view()
    {
        $view = $this->defaultView();
        $view["value"] = $this->getStringValue($this->model);
        return $view;
    }

    /**
     * Редактирование в гриде
     */
    public function xEditable()
    {
        // редактирование через чекбоксы
        return false;
    }

    /**
     * @param ActiveQuery $query
     * @return null
     */
    protected function search(ActiveQuery $query)
    {
        $params = $this->model->{$this->attr};
        if (!$params) return null;

        /** @var ActiveRecord $relatedClass */
        $table = $this->model->tableName();
        $relatedClass = $this->model->{"get" . ucfirst($this->relationName)}()->modelClass;
        $tableRelated = $relatedClass::tableName();

        $query->select(["$table.*, COUNT(*) AS countParams"]);

        $query->joinWith($this->relationName, $this->eagerLoading);
        $query->andFilterWhere(["$tableRelated.id" => $params]);
        $query->groupBy("$table.id");

        $query->andHaving(['countParams' => count($params)]);
    }
}