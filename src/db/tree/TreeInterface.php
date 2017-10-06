<?php

namespace lo\core\db\tree;

/**
 * Interface TreeInterface
 * @package lo\core\db\tree
 * @method getDescendants($depth)
 */
interface TreeInterface
{
    /**
     * @return int
     */
    public function getRootId();
}
