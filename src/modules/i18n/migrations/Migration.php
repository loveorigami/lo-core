<?php
namespace lo\core\modules\i18n\migrations;

use lo\core\db\Migration as BaseMigration;

class Migration extends BaseMigration
{
    public $tableGroup = "i18n";

    const TBL_MSG = 'message';
    const TBL_SOURCE = 'source_message';
}