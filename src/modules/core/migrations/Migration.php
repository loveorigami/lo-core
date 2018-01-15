<?php
namespace lo\core\modules\core\migrations;

use lo\core\db\Migration as BaseMigration;

class Migration extends BaseMigration
{
    public $tableGroup = "core";

    const TBL_TEMPLATE = 'template';
    const TBL_TIMELINE = 'timeline_event';
}