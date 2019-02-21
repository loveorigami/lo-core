<?php
namespace lo\core\modules\core\migrations\rbac;
use lo\core\helpers\BaseConstHelper;

class m180116_074024_system_menu extends Migration
{
    public function up()
    {
        $this->db->createCommand()->insert($this->menuTable(),[
            'name' => BaseConstHelper::B_MENU_CORE_ROOT,
            'parent' => '',
            'route' => '',
            'order' => 50,
            'data' => "return ['icon'=>'pie-chart'];",
        ])->execute();

        $pid = $this->getMenuIdByName(BaseConstHelper::B_MENU_CORE_ROOT);

        $this->db->createCommand()->insert($this->menuTable(),[
            'name' => BaseConstHelper::B_MENU_CORE_TRANSLATE,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CORE_TRANSLATE,
            'order' => 1,
            'data' => '',
        ])->execute();

        $this->db->createCommand()->insert($this->menuTable(),[
            'name' => BaseConstHelper::B_MENU_CORE_PLUGINS,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CORE_PLUGINS,
            'order' => 2,
            'data' => '',
        ])->execute();

        $this->db->createCommand()->insert($this->menuTable(),[
            'name' => BaseConstHelper::B_MENU_CORE_CACHE,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CORE_CACHE,
            'order' => 5,
            'data' => '',
        ])->execute();

        $this->db->createCommand()->insert($this->menuTable(),[
            'name' => BaseConstHelper::B_MENU_CORE_LOG,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CORE_LOG,
            'order' => 6,
            'data' => '',
        ])->execute();

        $this->db->createCommand()->insert($this->menuTable(),[
            'name' => BaseConstHelper::B_MENU_CORE_DUMPER,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CORE_DUMPER,
            'order' => 10,
            'data' => '',
        ])->execute();

        $this->db->createCommand()->insert($this->menuTable(),[
            'name' => BaseConstHelper::B_MENU_CORE_SETTINGS,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CORE_SETTINGS,
            'order' => 11,
            'data' => '',
        ])->execute();

        $this->db->createCommand()->insert($this->menuTable(),[
            'name' => BaseConstHelper::B_MENU_CORE_TEMPLATE,
            'parent' => $pid,
            'route' => BaseConstHelper::B_ROUTE_CORE_TEMPLATE,
            'order' => 12,
            'data' => '',
        ])->execute();

    }

    public function down()
    {
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CORE_TEMPLATE);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CORE_PLUGINS);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CORE_SETTINGS);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CORE_TRANSLATE);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CORE_CACHE);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CORE_LOG);
        $this->delMenuIdByName(BaseConstHelper::B_MENU_CORE_DUMPER);

        $this->delMenuIdByName(BaseConstHelper::B_MENU_CORE_ROOT);
    }

}