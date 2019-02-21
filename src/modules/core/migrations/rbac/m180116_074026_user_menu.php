<?php

namespace lo\core\modules\core\migrations\rbac;

use lo\core\helpers\BaseConstHelper;

class m180116_074026_user_menu extends Migration
{
    public function up()
    {
        $this->db->createCommand()->insert($this->menuTable(), [
            'name' => BaseConstHelper::B_MENU_USER_ROOT,
            'parent' => '',
            'route' => '',
            'order' => 20,
            'data' => "return ['icon'=>'users'];",
        ])->execute();

        $pid = $this->getMenuIdByName(BaseConstHelper::B_MENU_USER_ROOT);

        $this->db->createCommand()->insert($this->menuTable(), [
            'name' => BaseConstHelper::B_MENU_USER_USER,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_USER_USER,
            'order' => 1,
            'data' => '',
        ])->execute();
        $this->db->createCommand()->insert($this->menuTable(), [
            'name' => BaseConstHelper::B_MENU_USER_RBAC,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_USER_RBAC,
            'order' => 2,
            'data' => '',
        ])->execute();
        $this->db->createCommand()->insert($this->menuTable(), [
            'name' => BaseConstHelper::B_MENU_USER_CONSTRAINT,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_USER_CONSTRAINT,
            'order' => 10,
            'data' => '',
        ])->execute();
        $this->db->createCommand()->insert($this->menuTable(), [
            'name' => BaseConstHelper::B_MENU_USER_MENU,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_USER_MENU,
            'order' => 11,
            'data' => '',
        ])->execute();

    }

    public function down()
    {
        $this->delMenuIdByName(BaseConstHelper::B_MENU_USER_USER);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_USER_RBAC);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_USER_CONSTRAINT);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_USER_MENU);

        $this->delMenuIdByName(BaseConstHelper::B_MENU_USER_ROOT);
    }

}