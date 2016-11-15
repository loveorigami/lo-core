<?php
namespace lo\core\db\fields\relation;

/**
 * Class HasOneField
 * Поле для связей Has One. Интерфейс привязки в форме в виде выпадающего списка.
 *
 *  public function getCategories()
 *  {
 *      $models = Category::find()->published()->orderBy(["name" => SORT_ASC])->all();
 *      return ArrayHelper::map($models, "id", "name");
 *  }
 *
 *  "cat_id" => [
 *      "definition" => [
 *          "class" => fields\HasOneField::class,
 *          "title" => Yii::t('backend', 'Category'),
 *          "isRequired" => false,
 *          "data" => [$this, "getCategories"], // массив всех категорий (см. выше)
 *          "eagerLoading" => true,
 *          "numeric" => false,
 *          "showInGrid" => false,
 *          "relationName" => 'category', //relation getCategory
 *      ],
 *      "params" => [$this->owner, "cat_id"]
 *  ],
 *
 * @package lo\core\db\fields
 */
class OneToOneField extends RelationField
{

}