<?php

namespace lo\core\behaviors;

use voskobovich\linker\LinkerBehavior;

/**
 * Class ManyManySaver
 * Поведение для сохранения связанных через ONE-MANY или MANY-MANY записей
 * @package lo\core\behaviors
 *
 * As an example, let's assume you are dealing with entities like Book, Author and Review. The Book model has the following relationships:
 *
 * ```php
 *  public function getAuthors()
 *  {
 *      return $this->hasMany(Author::className(), ['id' => 'author_id'])
 *      ->viaTable('book_has_author', ['book_id' => 'id']);
 *  }
 *
 *  public function getReviews()
 *  {
 *      return $this->hasMany(Review::className(), ['id' => 'review_id']);
 *  }
 * ```
 * In the same model, the behaviour can be configured like so:
 *```php
 *  use lo\core\behaviors\ManyManySaver;
 *
 *  public function behaviors()
 *  {
 *      $arr = parent::behaviors();
 *
 *      $arr["many-cats"] = [
 *          'class' => ManyManySaver::class,
 *          'relations' => [
 *              'author_ids' => 'authors',
 *              'review_ids' => 'reviews',
 *          ],
 *      ];
 *
 *      return $arr;
 *  }
 * ```
 */
class ManyManySaver extends LinkerBehavior
{

}