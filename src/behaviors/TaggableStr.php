<?php

namespace lo\core\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\base\Event;
use yii\db\Query;

/**
 * Class Taggable
 * Поведение для сохранения связанных через MANY MANY записей
 * @package lo\core\behaviors
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class TaggableStr extends Behavior
{
    /**
     * @var ActiveRecord the owner of this behavior.
     */
    public $owner;
    /**
     * @var string
     */
    public $attribute = 'tagNames';
    /**
     * @var string
     */
    public $name = 'name';
    /**
     * @var string
     */
    public $tagFrequencyAttribute = 'frequency';
    /**
     * @var string
     */
    public $relation = 'tags';

    /**
     * Sort column
     * @var array|string
     */
    public $order = false;
    /**
     * Tag values
     * @var array|string
     */

    public $tagValues;
    /**
     * @var bool
     */
    public $asArray = false;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if ($name === $this->attribute) {
            return true;
        }

        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($this->owner->scenario == 'search') return $this->getTagIds();
        return $this->getTagNames();
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if ($name === $this->attribute) {
            return true;
        }

        return parent::canSetProperty($name, $checkVars);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
        $this->tagValues = $value;
    }

    /**
     * @inheritdoc
     */
    private function getTagNames()
    {
        $items = [];

        foreach ($this->owner->{$this->relation} as $tag) {
            $items[] = $tag->{$this->name};
        }

        return $this->asArray ? $items : implode(',', $items);
    }


    /**
     * Возвращает массив идентификаторов связанных записей
     * @return array
     */

    private function getTagIds()
    {
        $ids = [];
        $pk = $this->owner->getPrimaryKey();

        if (empty($pk))
            return $ids;

        $models = $this->owner->{$this->relation};

        foreach ($models AS $model)
            $ids[] = $model->id;

        return $ids;
    }


    /**
     * @param Event $event
     */
    public function afterSave($event)
    {
        if ($this->tagValues === null) {
            $this->tagValues = $this->owner->{$this->attribute};
        }

        if (!$this->owner->getIsNewRecord()) {
            $this->beforeDelete($event);
        }

        $names = array_unique(preg_split(
            '/\s*,\s*/u',
            preg_replace(
                '/\s+/u',
                ' ',
                is_array($this->tagValues)
                    ? implode(',', $this->tagValues)
                    : $this->tagValues
            ),
            -1,
            PREG_SPLIT_NO_EMPTY
        ));

        $relation = $this->owner->getRelation($this->relation);
        $pivot = $relation->via->from[0];
        /** @var ActiveRecord $class */
        $class = $relation->modelClass;
        $rows = [];
        $updatedTags = [];
        $order = 1;

        foreach ($names as $name) {
            $tag = $class::findOne([$this->name => $name]);

            if ($tag === null) {
                $tag = new $class();
                $tag->{$this->name} = $name;
            }
            if ($this->tagFrequencyAttribute !== false) {
                $frequency = $tag->getAttribute($this->tagFrequencyAttribute);
                $tag->setAttribute($this->tagFrequencyAttribute, ++$frequency);
                //$tag->{$this->frequency}++;
            }
            if ($tag->save()) {
                $updatedTags[] = $tag;
                $rows[] = $this->order ? [$this->owner->getPrimaryKey(), $tag->getPrimaryKey(), $order++] : [$this->owner->getPrimaryKey(), $tag->getPrimaryKey()];
            }
        }

        if (!empty($rows)) {
            $data = $this->order ? [key($relation->via->link), current($relation->link), $this->order] : [key($relation->via->link), current($relation->link)];
            $this->owner->getDb()
                ->createCommand()
                ->batchInsert($pivot, $data, $rows)
                ->execute();
        }

        $this->owner->populateRelation($this->relation, $updatedTags);
    }

    /**
     * @param Event $event
     */
    public function beforeDelete($event)
    {
        $relation = $this->owner->getRelation($this->relation);
        $pivot = $relation->via->from[0];
        /** @var ActiveRecord $class */
        $class = $relation->modelClass;
        $query = new Query();
        $pks = $query
            ->select(current($relation->link))
            ->from($pivot)
            ->where([key($relation->via->link) => $this->owner->getPrimaryKey()])
            ->column($this->owner->getDb());

        if (!empty($pks) && ($this->tagFrequencyAttribute !== false)) {
            $class::updateAllCounters([$this->tagFrequencyAttribute => -1], ['in', $class::primaryKey(), $pks]);
        }

        $this->owner->getDb()
            ->createCommand()
            ->delete($pivot, [key($relation->via->link) => $this->owner->getPrimaryKey()])
            ->execute();
    }

}