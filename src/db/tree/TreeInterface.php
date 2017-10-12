<?php

namespace lo\core\db\tree;

use lo\core\db\ActiveQuery;

/**
 * Interface TreeInterface
 * @package lo\core\db\tree
 */
interface TreeInterface
{
    /**
     * @return integer
     */
    public function getId(): int;

    /**
     * Id родительской категории
     * 1 - NestedSets
     * 0 - AdjacencyList
     * @return integer
     */
    public function getRootId(): int;

    /**
     * @return integer
     */
    public function getLevel(): int;

    /**
     * @return ActiveQuery
     */
    public function getParents();

    /**
     * @return ActiveQuery
     */
    public function getDescendants();
}
