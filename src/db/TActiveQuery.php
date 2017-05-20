<?php

namespace lo\core\db;

use paulzi\nestedsets\NestedSetsQueryTrait;

/**
 * Class TActiveQuery
 * Системный ActiveQuery. Предоставляет системные scopes. Содержит поведения для реализации древовидных структур
 * @package lo\core\db
 */
class TActiveQuery extends ActiveQuery
{
    use NestedSetsQueryTrait;
}
