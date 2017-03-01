<?php
/**
 * Andrey Lukyanov
 */

namespace lo\core\rbac;

use yii\rbac\Item;
use yii\rbac\Rule;

class GuestRule extends Rule
{
    /** @var string */
    public $name = 'GuestRule';

    /**
     * @param int $user
     * @param Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        return $user ? false : true;
    }
}
