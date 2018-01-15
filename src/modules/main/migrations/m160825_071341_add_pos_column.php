<?php
namespace lo\core\modules\main\migrations;

class m160825_071341_add_pos_column extends Migration
{
    public function up()
    {
        $this->addColumn(
            $this->tn(self::TBL_INC_ITEM_TO_GROUP),
            'pos',
            $this->smallInteger(6)->notNull()->defaultValue(0)
        );

        $this->addColumn(
            $this->tn(self::TBL_INC_ITEM_TO_GROUP),
            'frequency',
            $this->smallInteger(6)->notNull()->defaultValue(0)
        );
    }

    public function down()
    {
        $this->dropColumn($this->tn(self::TBL_INC_ITEM_TO_GROUP), 'pos');
        $this->dropColumn($this->tn(self::TBL_INC_ITEM_TO_GROUP), 'frequency');
    }

}