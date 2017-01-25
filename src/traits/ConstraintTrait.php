<?php
namespace lo\core\traits;

use lo\core\helpers\UserHelper;
use lo\core\modules\permission\models\Constraint;

/**
 * Class ConstraintTrait
 * Предоставляет функциональность по проверке прав доступа
 * @package lo\core\traits
 */
trait ConstraintTrait
{
    /** @return Constraint */
    public function getPermission()
    {
        $data = Constraint::findPermission(get_called_class(), UserHelper::getRole());
        return $data;
    }
}