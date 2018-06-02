<?php

namespace lo\core\db\tree;

use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;
use lo\core\db\query\TActiveQuery;
use paulzi\nestedsets\NestedSetsBehavior;
use Yii;

/**
 * Class TActiveRecord
 * Надстройка над ActiveRecord для реализации древовидных структур.
 * @package lo\core\db
 * @mixin NestedSetsBehavior
 * @property integer $id
 * @property integer $level
 * @method ActiveQuery published()
 */
abstract class TActiveRecord extends ActiveRecord
{
    use ArTreeTrait;

    /** Идентификатор корневой записи */
    const ROOT_ID = 1;

    /** @return int */
    public function getRootId(): int
    {
        return self::ROOT_ID;
    }

    /** @var int идентификатор родительской модели */
    public $parent_id = self::ROOT_ID;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors["nestedSets"] = [
            "class" => NestedSetsBehavior::class,
            "depthAttribute" => "level",
        ];

        return $behaviors;
    }

    /**
     * @return ActiveQuery
     */
    public function getParents()
    {
        return $this->getBehavior('nestedSets')->getParents();
    }

    /**
     * @return ActiveQuery
     */
    public function getDescendants()
    {
        return $this->getBehavior('nestedSets')->getDescendants();
    }

    /**
     * @inheritdoc
     * @return TActiveQuery
     */
    public static function find()
    {
        /** @var TActiveQuery $obj */
        $obj = Yii::createObject(TActiveQuery::class, [get_called_class()]);
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

        /** @var TActiveRecord $model */
        $model = $query->andWhere(["id" => $parent_id])->one();

        if (!$model) {
            return $arr;
        }

        /** @var ActiveQuery $query */
        $query = $model->getDescendants();
        $models = $query->published()->all();

        return $this->getList($models, $exclude, $attr, $arr);
    }

    /**
     * @param NestedSetsBehavior $node
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
