<?php
namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;
use lo\core\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class HasOneField
 * Поле для связей Has One. Интерфейс привязки в форме в виде выпадающего списка.
 * @package lo\core\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class HasOneField extends ListField
{
    /**
    * @var bool жадная загрузка
    */
    public $eagerLoading = true;

    /**
     * @var string имя связи
     */
    public $relation;

    /**
     * @var string имя атрибута связанной модели отображаемого в гриде
     */
    public $gridAttr = "name";

    /**
     * @inheritdoc
     */
    public $numeric = true;

    /**
     * Конструктор
     * @param ActiveRecord $model модель
     * @param string $attr атрибут
     * @param string $relation имя Has One связи
     */
    public function __construct(ActiveRecord $model, $attr, $relation, $config = [])
    {
        $this->relation = $relation;
        parent::__construct($model, $attr, $config);
    }
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $relation = $this->model->getRelation($this->relation);
        $rules[] = [$this->attr, 'exist', 'targetClass' => $relation->modelClass, 'targetAttribute' => key($relation->link), 'except'=>[ActiveRecord::SCENARIO_SEARCH]];
        return $rules;
    }
	
    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();
        $grid["value"] = function ($model, $index, $widget) {
            return ArrayHelper::getValue($model, "{$this->relation}.{$this->gridAttr}");
        };

        return $grid;
    }

    /**
     * @inheritdoc
     */
    protected function view()
    {
        $view = $this->defaultView();
        $view["value"] = ArrayHelper::getValue($this->model, "{$this->relation}.{$this->gridAttr}");
        return $view;
    }

    /**
     * Поиск
     * @param ActiveQuery $query запрос
     */
    public function search(ActiveQuery $query)
    {
        parent::search($query);
        if($this->eagerLoading && $this->search) {
            $query->with($this->relation);
        }
    }
}