<?php

namespace lo\core\modules\permission\migrations;

use lo\core\helpers\BaseUmodeHelper;
use lo\core\helpers\RbacHelper;
use lo\core\rbac\OwnModelRule;

class m151223_074604_edit_own_model extends Migration
{
    public function up()
    {
        $rule = new OwnModelRule();
        $this->auth->add($rule);

        $role = $this->auth->getRole(BaseUmodeHelper::ROLE_USER);

        $editOwnModelPermission = $this->auth->createPermission(RbacHelper::B_PERM_OWN);
        $editOwnModelPermission->ruleName = $rule->name;

        $this->auth->add($editOwnModelPermission);
        $this->auth->addChild($role, $editOwnModelPermission);
    }

    public function down()
    {
        $own = new OwnModelRule();
        $rule = $this->auth->getRule($own->name);
        $permission = $this->auth->getPermission(RbacHelper::B_PERM_OWN);

        $this->auth->remove($permission);
        $this->auth->remove($rule);
    }
}
