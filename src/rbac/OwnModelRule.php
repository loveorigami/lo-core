<?php
/**
 * Andrey Lukyanov
 */

namespace lo\core\rbac;

use yii\rbac\Item;
use yii\rbac\Rule;

class OwnModelRule extends Rule
{
    /** @var string */
    public $name = 'ownModelRule';

    /**
     * @param int   $user
     * @param Item  $item
     * @param array $params
     * - model: model to check owner
     * - attribute: attribute that will be compared to user ID
     * @return bool
     */
    public function execute($user, $item, $params): bool
    {
        $attribute = $params['attribute'] ?? 'author_id';

        return $user && isset($params['model']) && $user === $params['model']->getAttribute($attribute);
    }
}
