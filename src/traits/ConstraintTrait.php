<?php

namespace lo\core\traits;

use lo\core\helpers\BaseUmodeHelper;
use lo\modules\core\permission\models\Constraint;

/**
 * Class ConstraintTrait
 * Предоставляет функциональность по проверке прав доступа
 *
 * @package lo\core\traits
 */
trait ConstraintTrait
{
    /** @return Constraint */
    public function getPermission(): ?Constraint
    {
        return Constraint::findPermission(static::class, BaseUmodeHelper::getAuthRole());
    }
}
