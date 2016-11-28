<?php
namespace lo\core\db\fields\ajax;

use lo\core\db\ActiveQuery;
use lo\core\db\fields\HasOneField;
use lo\core\grid\Select2Column;
use yii\helpers\ArrayHelper;

/**
 * Class AjaxField
 * Базовый класс ajax полей.
 * @package lo\core\db\fields\ajax
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 * @property $relationName
 * @property $loadUrl
 */
abstract class AjaxField extends HasOneField
{
    public $gridAttr = 'name';
    public $eagerLoading = true;

    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();

        // ($model, $index, $widget)
        $grid["value"] = function ($model) {
            return ArrayHelper::getValue($model, "{$this->relationName}.{$this->gridAttr}");
        };
        $grid["contentOptions"] = ['style'=>'min-width: 200px;'];
        $grid["initValueText"] = ArrayHelper::getValue($this->model, "{$this->relationName}.{$this->gridAttr}");
        $grid["class"] = Select2Column::class;
        $grid["loadUrl"] = $this->getLoadUrl();

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