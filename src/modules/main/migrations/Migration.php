<?php
namespace lo\core\modules\main\migrations;

use lo\core\db\Migration as BaseMigration;

class Migration extends BaseMigration
{
    public $tableGroup = "core";

    const TBL_MENU = 'menu';
    const TBL_INC_ITEM = 'include_item';
    const TBL_INC_ITEM_TO_GROUP = 'include_item_to_group';
    const TBL_INC_GROUP = 'include_group';
}