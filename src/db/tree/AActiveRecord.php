<?php

namespace lo\core\db\tree;

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
 * @property integer $parent_id
 * @property integer $level
 * @property AActiveRecord [] $descendantsOrdered
 */
abstract class AActiveRecord extends ActiveRecord implements TreeInterface
{
    /**
     * Идентификатор корневой записи
     */
    const ROOT_ID = 0;

    /**
     * @return int
     */
    public function getRootId()
    {
        return self::ROOT_ID;
    }

    /**
     * @var array
     */
    public $children;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors["adjacencyList"] = [
            'class' => AdjacencyListBehavior::class,
            'parentAttribute' => 'parent_id',
            'sortable' => [
                'sortAttribute' => 'pos',
                'step' => 10
            ],
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
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->parent_id > 0) {
                $parentNodeLevel = static::find()->select('level')->where(['id' => $this->parent_id])->scalar();
                $this->level = $parentNodeLevel + 1;
            } else {
                $this->level = 1;
            }
            return true;
        } else {
            return false;
        }
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
        $arr = [];

        $query = static::find();

        if ($perm = $this->getPermission()) {
            $perm->applyConstraint($query);
        }

        /**
         * @var AActiveRecord [] $models
         */
        $models = $query->roots()->with('children')->all();

        if (!empty($models)) {
            $models = $this->buildRecursive($models);
        }

        $descendants = [];

        if (!$this->isNewRecord) {
            $descendants = $this->getDescendants()->all();
            $descendants[] = $this;
        }

        if (!empty($exclude)) {
            /** @var TActiveRecord $exModels */
            $exModels = static::find()->where(["id" => $exclude])->all();

            foreach ($exModels AS $exModel) {
                $descendants[] = $exModel;
                $exDescendants = $exModel->getDescendants()->all();
                $descendants = array_merge($descendants, $exDescendants);
            }
        }

        $i = 0;
        foreach ($models AS $m) {
            /** @var TActiveRecord $m */
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
     * @param AActiveRecord [] $items
     * @param int $level
     * @param int $foolproof
     * @return array
     */
    protected function buildRecursive($items, $level = 1, $foolproof = 20)
    {
        $data = [];
        foreach ($items as $item) {
            $data[] = $item;
            if ($item->level != $level) {
                $item->level = $level;
                $item->save(false);
            }
            $childs = $item->descendantsOrdered;
            if ($foolproof && $childs)
                $data = array_merge($data, $this->buildRecursive($childs, $level + 1, $foolproof - 1));
        }
        return $data;
    }
}
