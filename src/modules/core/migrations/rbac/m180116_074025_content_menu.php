<?php

namespace lo\core\modules\core\migrations\rbac;

use lo\core\helpers\BaseConstHelper;

class m180116_074025_content_menu extends Migration
{
    public function up()
    {
        $this->db->createCommand()->insert($this->menuTable(), [
            'name' => BaseConstHelper::B_MENU_CONTENT_ROOT,
            'parent' => '',
            'route' => '',
            'order' => 3,
            'data' => "return ['icon'=>'edit'];",
        ])->execute();

        $pid = $this->getMenuIdByName(BaseConstHelper::B_MENU_CONTENT_ROOT);

        $this->db->createCommand()->insert($this->menuTable(), [
            'name' => BaseConstHelper::B_MENU_CONTENT_INCLUDE,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CONTENT_INCLUDE,
            'order' => 4,
            'data' => '',
        ])->execute();
        $this->db->createCommand()->insert($this->menuTable(), [
            'name' => BaseConstHelper::B_MENU_CONTENT_GALLERY,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CONTENT_GALLERY,
            'order' => 5,
            'data' => '',
        ])->execute();
        $this->db->createCommand()->insert($this->menuTable(), [
            'name' => BaseConstHelper::B_MENU_CONTENT_FILES,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CONTENT_FILES,
            'order' => 6,
            'data' => '',
        ])->execute();
        $this->db->createCommand()->insert($this->menuTable(), [
            'name' => BaseConstHelper::B_MENU_CONTENT_MENU,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CONTENT_MENU,
            'order' => 7,
            'data' => '',
        ])->execute();
        $this->db->createCommand()->insert($this->menuTable(), [
            'name' => BaseConstHelper::B_MENU_CONTENT_COMMENTS,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CONTENT_COMMENTS,
            'order' => 10,
            'data' => '',
        ])->execute();
    }

    public function down()
    {
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CONTENT_COMMENTS);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CONTENT_INCLUDE);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CONTENT_GALLERY);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CONTENT_FILES);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CONTENT_MENU);

        $this->delMenuIdByName(BaseConstHelper::B_MENU_CONTENT_ROOT);
    }

}