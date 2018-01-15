<?php

namespace lo\core\modules\permission\migrations;

use lo\core\helpers\BaseUmodeHelper;

class m151223_074605_root_rule extends Migration
{
    public function up()
    {
        $role = $this->auth->getRole(BaseUmodeHelper::ROLE_ROOT);
        $permission = $this->auth->createPermission('/*');
        $this->auth->add($permission);
        $this->auth->addChild($role, $permission);
    }

    public function down()
    {
        $permission = $this->auth->getPermission('/*');
        $this->auth->remove($permission);
    }
}
