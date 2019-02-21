<?php

namespace lo\core\modules\permission\migrations;

use lo\core\helpers\BaseUmodeHelper;
use lo\core\helpers\RbacHelper;

class m151223_074606_crud_rule extends Migration
{
    public function up()
    {
        $root = $this->auth->getRole(BaseUmodeHelper::ROLE_ROOT);
        $own = $this->auth->getPermission(RbacHelper::B_PERM_OWN);

        $edit = $this->auth->createPermission(RbacHelper::B_UPDATE);
        $delete = $this->auth->createPermission(RbacHelper::B_DELETE);
        $this->auth->add($edit);
        $this->auth->add($delete);

        $this->auth->addChild($own, $edit);
        $this->auth->addChild($own, $delete);

        $this->auth->addChild($root, $edit);
        $this->auth->addChild($root, $delete);
    }

    public function down()
    {
        $edit = $this->auth->getPermission(RbacHelper::B_UPDATE);
        $this->auth->remove($edit);
    }
}
