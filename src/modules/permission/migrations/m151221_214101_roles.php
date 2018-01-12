<?php

namespace lo\core\modules\permission\migrations;

use lo\core\helpers\BaseUmodeHelper;

class m151221_214101_roles extends Migration
{
    public function up()
    {
        $this->auth->removeAll();

        $user = $this->auth->createRole(BaseUmodeHelper::ROLE_USER);
        $this->auth->add($user);

        $author = $this->auth->createRole(BaseUmodeHelper::ROLE_AUTHOR);
        $this->auth->add($author);
        $this->auth->addChild($author, $user);

        $editor = $this->auth->createRole(BaseUmodeHelper::ROLE_EDITOR);
        $this->auth->add($editor);
        $this->auth->addChild($editor, $user);
        $this->auth->addChild($editor, $author);

        $root = $this->auth->createRole(BaseUmodeHelper::ROLE_ROOT);
        $this->auth->add($root);
        $this->auth->addChild($root, $editor);
        $this->auth->addChild($root, $author);
        $this->auth->addChild($root, $user);

        $this->auth->assign($root, 1);
        $this->auth->assign($author, 2);
        $this->auth->assign($user, 3);
    }

    public function down()
    {
        $this->auth->remove($this->auth->getRole(BaseUmodeHelper::ROLE_ROOT));
        $this->auth->remove($this->auth->getRole(BaseUmodeHelper::ROLE_EDITOR));
        $this->auth->remove($this->auth->getRole(BaseUmodeHelper::ROLE_AUTHOR));
        $this->auth->remove($this->auth->getRole(BaseUmodeHelper::ROLE_USER));
    }
}
