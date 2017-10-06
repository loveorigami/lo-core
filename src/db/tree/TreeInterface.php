<?php

namespace lo\core\db\tree;

/**
 * Interface TreeInterface
 * @package lo\core\db\tree
 * @method \lo\core\db\ActiveRecord::getDescendants($depth = 0)
 */
interface TreeInterface
{
    /**
     * @return int
     */
    public function getRootId();
}
