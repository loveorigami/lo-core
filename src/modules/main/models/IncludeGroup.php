<?php

namespace lo\core\modules\main\models;

use lo\core\behaviors\MatchSuitable;
use lo\core\behaviors\TaggableStr;
use lo\core\db\ActiveRecord;
use yii\db\ActiveQueryInterface;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * Class IncludeGroup
 * Группа включаемых областей
 * @property string $includesIdsStr
 * @property array $includesIds
 * @property IncludeItem $includes
 * @mixin MatchSuitable
 */
class IncludeGroup extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    private $_includesMap;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return "{{%core__include_group}}";
    }

    /**
     * @inheritdoc
     */
    public function metaClass()
    {
        return IncludeGroupMeta::class;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $arr = parent::behaviors();

        $arr["tags"] = [
            'class' => TaggableStr::class,
            'attribute' => 'includesIds',
            'relation' => 'includes',
            'tagFrequencyAttribute' => false,
            'order' => 'pos',
        ];

        $arr["matchSuitable"] = MatchSuitable::class;

        return $arr;
    }

    /**
     * Связь с включаемымы областями
     * @return ActiveQueryInterface
     */
    public function getIncludes()
    {
        return $this->hasMany(IncludeItem::class, ['id' => 'include_id'])
            ->viaTable('{{%core__include_item_to_group}}', ['group_id' => 'id']);
    }


    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        $val = parent::__get($name);

        if ($name == "includes" AND !empty($val)) {
            $this->sortIncludes($val, $this->id);
        }

        return $val;
    }


    /**
     * Сортировка включаемых областей
     * @param IncludeItem[] $includes включаемые области
     * @param int $id идентификатор модели
     */
    public function sortIncludes(&$includes, $id)
    {

        $map = $this->getIncludesMap($id);

        uasort($includes, function ($val1, $val2) use ($map) {

            $key1 = array_search($val1->id, $map);
            $key2 = array_search($val2->id, $map);

            if ($key1 > $key2)
                return 1;
            elseif ($key1 < $key2)
                return -1;
            else
                return 0;

        });

    }

    /**
     * Вормирует массив для сортировки включаемых областей
     * @param int $id идентификатор группы
     * @return array
     */
    public function getIncludesMap($id)
    {
        if ($this->_includesMap === null) {

            $query = new Query();

            $rows = $query
                ->select(["pos", "include_id"])
                ->from("{{%core__include_item_to_group}}")
                ->andWhere(["group_id" => $id])
                ->orderBy(["pos" => SORT_ASC])
                ->all();

            $this->_includesMap = ArrayHelper::map($rows, "pos", "include_id");

        }
        return $this->_includesMap;
    }

}