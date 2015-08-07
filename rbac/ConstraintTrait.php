<?php
namespace lo\core\rbac;

use lo\modules\core\models\Constraint;

/**
 * Class PermissionTrait
 * Предоставляет функциональность по проверке прав доступа
 * @package lo\core\rbac
 */
trait ConstraintTrait
{
    /**
     * Возвращает модель правил доступа
     * @return Permission
     */

    public function getPermission()
    {
        return Constraint::findPermission(get_class($this));
    }

}