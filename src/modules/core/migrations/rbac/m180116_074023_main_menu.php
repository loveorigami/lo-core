<?php
namespace lo\core\modules\core\migrations\rbac;
use lo\core\helpers\BaseConstHelper;

class m180116_074023_main_menu extends Migration
{
    public function up()
    {
        $this->db->createCommand()->insert($this->menuTable(),[
            'name' => BaseConstHelper::B_MENU_MAIN_ROOT,
            'parent' => '',
            'route' => BaseConstHelper::B_ROUTE_MAIN_ROOT,
            'order' => 1,
            'data' => "return ['icon'=>'dashboard'];",
        ])->execute();

    }

    public function down()
    {
        $this->delMenuIdByName(BaseConstHelper::B_MENU_MAIN_ROOT);
    }

}