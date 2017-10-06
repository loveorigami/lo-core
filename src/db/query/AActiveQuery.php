<?php

namespace lo\core\db\query;

use lo\core\db\ActiveQuery;
use paulzi\adjacencyList\AdjacencyListQueryTrait;

/**
 * Class AActiveQuery
 * Системный ActiveQuery. Предоставляет системные scopes. Содержит поведения для реализации древовидных структур
 * @package lo\core\db
 */
class AActiveQuery extends ActiveQuery
{
    use AdjacencyListQueryTrait;
}
