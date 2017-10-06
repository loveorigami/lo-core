<?php

namespace lo\core\db\tree;

use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;
use lo\core\db\query\AActiveQuery;
use paulzi\adjacencyList\AdjacencyListBehavior;
use Yii;

/**
 * Class AActiveRecord
 * Надстройка над ActiveRecord для реализации древовидных структур.
 * @package lo\core\db
 * @mixin AdjacencyListBehavior
 * @property integer $id
 * @property integer $level
 * @method ActiveQuery getDescendants()
 */
abstract class AActiveRecord extends ActiveRecord
{
    const ROOT_ID = 0;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors["adjacencyList"] = [
            'class' => AdjacencyListBehavior::class,
            'parentAttribute' => 'parent_id',
            'sortable' => false,
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     * @return AActiveQuery
     */
    public static function find()
    {
        /** @var AActiveQuery $obj */
        $obj = Yii::createObject(AActiveQuery::class, [get_called_class()]);
        return $obj;
    }

    /**
     * Возвращает массив для заполнения списка выбора родителя модели
     * @param int $parent_id
     * @param array $exclude массив id моделей ветки которых необходимо исключить из списка
     * @param string $attr имя отображаемого атрибута
     * @return array
     */
    public function getListTreeData($parent_id = self::ROOT_ID, $exclude = [], $attr = "name")
    {
        $arr = [self::ROOT_ID => Yii::t('common', 'Root')];

        $query = static::find();

        if ($perm = $this->getPermission()) {
            $perm->applyConstraint($query);
        }

        /**
         * @var AActiveRecord $model
         */
        $model = $query->andWhere(["id" => $parent_id])->one();

        if (!$model) {
            return $arr;
        }

        $models = $model->getDescendants()->published()->all();

        $descendants = [];

        if (!$this->isNewRecord) {
            $descendants = $this->getDescendants()->all();
            $descendants[] = $this;
        }

        if (!empty($exclude)) {
            /** @var AActiveRecord $exModels */
            $exModels = static::find()->where(["id" => $exclude])->all();

            foreach ($exModels AS $exModel) {
                $descendants[] = $exModel;
                $exDescendants = $exModel->getDescendants()->all();
                $descendants = array_merge($descendants, $exDescendants);
            }
        }

        $i = 0;

        foreach ($models AS $m) {
            /** @var AActiveRecord $m */
            if ($this->inArray($descendants, $m)) {
                $i++;
                continue;
            }

            $separator = '';
            $separator .= str_repeat("&ensp;", $m->level);
            $separator .= ($m->level != 0) ? (
                (isset($models[$i + 1])) && ($m->level == $models[$i + 1]->level)
            ) ? '┣ ' : '┗ ' : '';

            $arr[$m->id] = $separator . $m->$attr;
            $i++;
        }

        return $arr;
    }

    /**
     * Содердится ли в массиве $models модель $model
     * @param ActiveRecord[] $models
     * @param ActiveRecord $model
     * @return bool
     */
    public function inArray($models, $model)
    {
        foreach ($models AS $m) {
            if ($m->id == $model->id)
                return true;
        }
        return false;
    }

    /**
     * Возвращает массив для заполнения списка выбора
     * @param int $parent_id идентификатор родителя
     * @param string $attr имя отображаемого атрибута
     * @return array
     */
    public function getDataByParent($parent_id = self::ROOT_ID, $attr = "name")
    {
        $arr = [];
        $query = static::find();

        /** @var AActiveRecord $model */
        $model = $query->andWhere(["id" => $parent_id])->one();

        if (!$model)
            return $arr;

        $models = $model->getDescendants()->published()->all();

        foreach ($models AS $m) {
            /** @var AActiveRecord $m */
            $arr[$m->id] = str_repeat("&nbsp", $m->level) . $m->$attr;
        }

        return $arr;
    }

    /**
     * Возвращает массив для хлебных крошек
     * @param int|AActiveRecord $modelArg модель или ее идентификатор
     * @param callable $route функция возвращающая маршрут/url. Принимает в себя параметром экземпляр модели
     * @param string $attr имя атрибута для label
     * @return array
     */
    public function getBreadCrumbsItems($modelArg, $route, $attr = "name")
    {

        if (is_object($modelArg)) {
            $model = $modelArg;
        } else {
            $model = static::find()->where(["id" => $modelArg])->one();
        }

        $models = $model->getParents()->all();
        $models[] = $model;

        $arr = [];

        foreach ($models AS $model) {

            if (empty($model->$attr))
                continue;

            $arr[] = [
                "url" => call_user_func($route, $model),
                "label" => $model->$attr,
            ];
        }

        return $arr;
    }

    /**
     * Возвращает массив идентификаторов дочерних элементов и текущего элемента
     * @return array
     */
    public function getFilterIds(): array
    {
        $arr[] = $this->id;
        $models = $this->getDescendants()->published()->all();
        foreach ($models As $model) {
            /** @var AActiveRecord $model */
            $arr[] = $model->id;
        }
        return $arr;
    }

    /**
     * @param AdjacencyListBehavior $node
     * @param $depth
     * @param $status
     * @return array
     */
    public function getTreeByStatus($node, $depth, $status): array
    {
        $node->populateTree($depth);
        return $this->treeListData($node, $depth, $status);
    }

    /**
     * @param $node
     * @param $depth
     * @param $status
     * @param int $level
     * @return array
     */
    protected function treeListData($node, $depth, $status, $level = 0): array
    {
        $result = [];
        foreach ($node->children as $child) {
            if ($child->status == $status) {
                $result[$child->id] = $child;
                if ($level + 1 < $depth) {
                    $result = $result + $this->treeListData($child, $depth, $status, $level + 1);
                }
            }
        }
        return $result;
    }
}
