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
 * @property integer $id
 * @property integer $parent_id
 * @property integer $level
 * @property AActiveRecord [] $descendantsOrdered
 *
 * @mixin AdjacencyListBehavior
 */
abstract class AActiveRecord extends ActiveRecord
{
    use ArTreeTrait;

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
        $query = static::find();

        if ($perm = $this->getPermission()) {
            $perm->applyConstraint($query);
        }

        $children = $this->getChildrenRelation(3);

        /** @var TreeInterface [] $models */
        $models = $query->roots()->with($children)->all();

        if (!empty($models)) {
            $models = $this->buildRecursive($models);
        }

        $arr = [null => Yii::t('common', 'Root') . ' ' . $parent_id];
        return $this->getList($models, $exclude, $attr, $arr);
    }

    /**
     * @param [] $items
     * @param int $level
     * @return array
     */
    protected function buildRecursive($items, $level = 1)
    {
        $data = [];
        /** @var AActiveRecord $node */
        foreach ($items as $node) {
            $data[] = $node;
            if ($node->level != $level) {
                $node->level = $level;
                $node->save(false);
            }

            if ($node->children) {
                $data = array_merge($data, $this->buildRecursive($node->children, $level + 1));
            }
        }
        return $data;
    }
}
