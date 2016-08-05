<?php
namespace lo\core\behaviors;

use creocoder\nestedsets\NestedSetsBehavior;

/**
 * Class NestedSet
 * Исправляет баги в оригинальном NestedSet
 * @package lo\core\behaviors
 */
class NestedSet extends NestedSetsBehavior
{
    /**
     * @inheritdoc
     */
    public function afterFind($event)
    {
    }
}