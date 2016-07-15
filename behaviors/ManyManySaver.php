<?php

namespace lo\core\behaviors;

use yii\base\Behavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class ManyManySaver
 * Поведение для сохранения связанных через MANY MANY записей
 * @package lo\core\behaviors
 *
 *```php
 *  use lo\core\behaviors\ManyManySaver;
 *
 *  protected $_catIds;
 *
 *  public function behaviors()
 *  {
 *      $arr = parent::behaviors();
 *
 *      $arr["many-cats"] = [
 *          'class' => ManyManySaver::class,
 *          'names' => ['cats'] // relation getCats()
 *      ];
 *
 *      return $arr;
 *  }
 * ```
 *
 * Заполняем данными идентификаторы связвнных категорий
 *
 * ```php
 *  public function getCatIds()
 *  {
 *      if (!is_array($this->_tabIds) AND !$this->isNewRecord) {
 *          $this->_tabIds = $this->getManyManyIds("cats"); // relation getCats
 *      }
 *
 *      return $this->_tabIds;
 *  }
 *
 *  public function setCatIds($catIds)
 *  {
 *      $this->_catIds = $catIds;
 *  }
 * ```
 *
 *  Связь через промежуточную таблицу
 *
 * ```php
 *  public function getCats()
 *  {
 *      return $this->hasMany(Category::class, ['id' => 'cat_id'])->viaTable('{{%category_item}}', ['item_id' => 'id']);
 *  }
 * ```
 */
class ManyManySaver extends Behavior
{

    const ATTR_SUFF = "Ids";

    /**
     * @var ActiveRecord the owner of this behavior.
     */
    public $owner;

    /**
     * @var array массив имен связей для сохранения
     */
    public $names = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => [$this, "afterSave"],
            ActiveRecord::EVENT_AFTER_UPDATE => [$this, "afterSave"],
        ];
    }

    /**
     * Сохранение связей
     */
    public function afterSave()
    {
        /**
         * @var ActiveQuery $query
         * @var ActiveRecord $modelClass
         */
        $this->owner->setIsNewRecord(false);

        foreach ($this->names AS $name) {
            $attr = $this->getAttributeName($name);

            if ($this->owner->$attr !== null) {

                $query = $this->owner->{"get" . ucfirst($name)}();
                $modelClass = $query->modelClass;
                $related = $query->all();

                foreach ($related AS $rel) {
                    $this->owner->unlink($name, $rel, true);
                }

                if (empty($this->owner->$attr))
                    continue;

                $newRelated = $modelClass::find()->where(["id" => $this->owner->$attr])->all();

                usort($newRelated, function ($val1, $val2) use ($attr) {

                    $key1 = array_search($val1->id, $this->owner->$attr);
                    $key2 = array_search($val2->id, $this->owner->$attr);

                    if ($key1 > $key2)
                        return 1;
                    elseif ($key1 < $key2)
                        return -1;
                    else
                        return 0;
                });

                foreach ($newRelated as $newRel) {
                    $this->owner->link($name, $newRel);
                }
            }
        }
    }

    /**
     * Возвращает имя атрибута хранящего идентификаторы привязываемых записей
     * @param string $name имя звязи
     * @return string
     */
    public function getAttributeName($name)
    {
        return $name . static::ATTR_SUFF;
    }

    /**
     * Возвращает массив идентификаторов связанных записей
     * @param string $name имя связи
     * @return array
     */
    public function getManyManyIds($name)
    {
        $ids = [];

        $pk = $this->owner->getPrimaryKey();

        if (empty($pk))
            return $ids;

        $models = $this->owner->$name;

        foreach ($models AS $model)
            $ids[] = $model->id;

        return $ids;
    }

}