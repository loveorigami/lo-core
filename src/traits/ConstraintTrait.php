<?php
namespace lo\core\traits;

use lo\core\modules\permission\models\Constraint;
use Yii;

/**
 * Class ConstraintTrait
 * Предоставляет функциональность по проверке прав доступа
 * @package lo\core\traits
 */
trait ConstraintTrait
{
    protected $_role;

    /** @return Constraint */
    public function getPermission()
    {
        $data = Constraint::findPermission(get_called_class(), $this->getRole());
        return $data;
    }

    /** @return string default role */
    protected function getRole()
    {
        if (!$this->_role) {
            $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
            $this->_role = key($roles);
        }
        return $this->_role;
    }
}