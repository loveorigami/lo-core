<?php

namespace lo\core\modules\permission\migrations;

use lo\core\helpers\BaseUmodeHelper;

class m151221_214102_init_permissions extends Migration
{
    public function up()
    {
        $managerRole = $this->auth->getRole(BaseUmodeHelper::ROLE_AUTHOR);
        $administratorRole = $this->auth->getRole(BaseUmodeHelper::ROLE_ROOT);
        $loginToBackend = $this->auth->createPermission('loginToBackend');
        $this->auth->add($loginToBackend);
        $this->auth->addChild($managerRole, $loginToBackend);
        $this->auth->addChild($administratorRole, $loginToBackend);
    }

    public function down()
    {
        $this->auth->remove($this->auth->getPermission('loginToBackend'));
    }
}
