<?php
namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;
use lo\core\db\ActiveQuery;

/**
 * Class RelationField
 * Поле для работы с атрибутами с наличием relation связи
 *
 * В основной модели добавляем атрибут relation и связь
 * ```php
 *  public $_image;
 *
 *  public function getContent()
 *  {
 *      return $this->hasOne(Content::class, ['page_id' => 'id']);
 *  }
 * ```
 *
 * В мета-модели:
 * ```php
 *  "_image" => [
 *      "definition" => [
 *          "class" => fields\RelationField::class,
 *          "title" => Yii::t('backend', 'image'),
 *          "showInExtendedFilter" => false,
 *      ],
 *      "params" => [$this->owner, "_image", ['content', 'image']] // relation and relationAttr
 *  ],
 * ```
 * @package lo\core\db\fields
 *
 */
class RelationField extends Field
{
    /**
     * Жадная загрузка
     * @var bool
     */
    public $eagerLoading = true;

    /**
     * @var bool
     */
    public $checkExist = false;

    /**
     * Конструктор
     * @param ActiveRecord $model модель
     * @param string $attr атрибут
     * @param array $relation имя Has One связи и название поля
     * @param array $config доп настройки
     */
    public function __construct(ActiveRecord $model, $attr, array $relation, $config = [])
    {
        $this->relation = $relation[0];
        $this->relationAttr = $relation[1];
        parent::__construct($model, $attr, $config);
    }

    /**
     * Отображение в гриде
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();
        $grid["value"] = function ($model, $index, $widget) {
            return $this->getStringValue($model);
        };
        return $grid;
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
     * Поиск
     * @param ActiveQuery $query
     */
    protected function search(ActiveQuery $query)
    {
        /**
         * @var ActiveRecord $relatedClass
         */
        $table = $this->model->tableName();
        $relatedClass = $this->model->{"get" . ucfirst($this->relation)}()->modelClass;
        $tableRelated = $relatedClass::tableName();

        $query->
        joinWith($this->relation, $this->eagerLoading)->
        andFilterWhere(['like', $tableRelated . '.' . $this->relationAttr, $this->model->{$this->attr}])->
        groupBy("$table.id");
    }

    /**
     * Возвращает строковое представление связанных моделей для отображения в гриде и при детальном просмотре
     * @param ActiveRecord $model
     * @return string
     */
    protected function getStringValue($model)
    {
        if($model->{$this->relation} === null ) return null;
        return $model->{$this->relation}->{$this->relationAttr};
    }
}