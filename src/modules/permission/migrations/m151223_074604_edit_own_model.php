<?php

namespace lo\core\modules\permission\migrations;

use lo\core\helpers\BaseUmodeHelper;
use lo\core\rbac\OwnModelRule;

class m151223_074604_edit_own_model extends Migration
{
    public function up()
    {
        $rule = new OwnModelRule();
        $this->auth->add($rule);
        $role = $this->auth->getRole(BaseUmodeHelper::ROLE_USER);
        $editOwnModelPermission = $this->auth->createPermission('OwnModelRule');
        $editOwnModelPermission->ruleName = $rule->name;
        $this->auth->add($editOwnModelPermission);
        $this->auth->addChild($role, $editOwnModelPermission);
    }

    public function down()
    {
        $permission = $this->auth->getPermission('OwnModelRule');
        $rule = $this->auth->getRule('ownModelRule');
        $this->auth->remove($permission);
        $this->auth->remove($rule);
    }
}
