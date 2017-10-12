<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 12.10.2017
 * Time: 10:00
 */

namespace lo\core\db\tree;

use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;

/**
 * Trait ArTreeTrait
 * @package lo\core\db\tree
 * @method ActiveQuery find()
 */
trait ArTreeTrait
{
    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return integer
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Возвращает массив для хлебных крошек
     * @param int|TreeInterface $modelArg модель или ее идентификатор
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

        if (!$model) return [];

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
     * Содердится ли в массиве $models модель $model
     * @param TreeInterface [] $models
     * @param TreeInterface $model
     * @return bool
     */
    public function inArray($models, $model)
    {
        foreach ($models AS $m) {
            if ($m->getId() == $model->getId())
                return true;
        }
        return false;
    }

    /**
     * Возвращает массив идентификаторов дочерних элементов и текущего элемента
     * @return array
     */
    public function getFilterIds(): array
    {
        $arr[] = $this->id;

        /** @var ActiveQuery $query */
        $query = $this->getDescendants();
        $models = $query->published()->all();

        foreach ($models As $model) {
            /** @var TreeInterface $model */
            $arr[] = $model->getId();
        }
        return $arr;
    }

    /**
     * @param TreeInterface [] $models
     * @param $exclude
     * @param $attr
     * @param [] $arr
     * @return mixed
     */
    protected function getList($models, $exclude, $attr, $arr)
    {
        $descendants = [];

        if (!$this->isNewRecord) {
            /** @var TreeInterface $this $descendants */
            $descendants = $this->getDescendants()->all();
            $descendants[] = $this;
        }

        if (!empty($exclude)) {
            /** @var ActiveRecord $exModels */
            $exModels = static::find()->where(["id" => $exclude])->all();

            foreach ($exModels AS $exModel) {
                $descendants[] = $exModel;
                $exDescendants = $exModel->getDescendants()->all();
                $descendants = array_merge($descendants, $exDescendants);
            }
        }

        $i = 0;

        foreach ($models AS $m) {
            /** @var TreeInterface $m */
            if ($this->inArray($descendants, $m)) {
                $i++;
                continue;
            }

            $separator = '';
            $separator .= str_repeat("&ensp;", $m->getLevel());
            $separator .= ($m->getLevel() != 0) ? (
                (isset($models[$i + 1])) && ($m->getLevel() == $models[$i + 1]->getLevel())
            ) ? '┣ ' : '┗ ' : '';

            $arr[$m->getId()] = $separator . $m->$attr;
            $i++;
        }

        return $arr;
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

        /** @var TreeInterface $model */
        $model = $query->andWhere(["id" => $parent_id])->one();

        if (!$model)
            return $arr;

        $models = $model->getDescendants()->published()->all();

        foreach ($models AS $m) {
            /** @var TreeInterface $m */
            $arr[$m->getId()] = str_repeat("&nbsp", $m->getLevel()) . $m->$attr;
        }

        return $arr;
    }
}