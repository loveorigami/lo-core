<?php

namespace lo\core\modules\main\migrations;

class m150219_073312_create_groups_to_includes_table extends Migration
{
    public function safeUp()
    {
        $this->createTable($this->tn(self::TBL_INC_ITEM_TO_GROUP), [
            'id' => $this->primaryKey(),
            'include_id' => $this->integer()->notNull()->defaultValue(0),
            'group_id' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->addForeignKey(
            $this->fk(self::TBL_INC_ITEM_TO_GROUP, self::TBL_INC_ITEM),
            $this->tn(self::TBL_INC_ITEM_TO_GROUP), 'include_id',
            $this->tn(self::TBL_INC_ITEM), 'id',
            'CASCADE', 'CASCADE'
        );

        $this->addForeignKey(
            $this->fk(self::TBL_INC_ITEM_TO_GROUP, self::TBL_INC_GROUP),
            $this->tn(self::TBL_INC_ITEM_TO_GROUP), 'group_id',
            $this->tn(self::TBL_INC_GROUP), 'id',
            'CASCADE', 'CASCADE'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey(
            $this->fk(self::TBL_INC_ITEM_TO_GROUP, self::TBL_INC_ITEM),
            $this->tn(self::TBL_INC_ITEM_TO_GROUP)
        );

        $this->dropForeignKey(
            $this->fk(self::TBL_INC_ITEM_TO_GROUP, self::TBL_INC_GROUP),
            $this->tn(self::TBL_INC_ITEM_TO_GROUP)
        );

        $this->dropTable($this->tn(self::TBL_INC_ITEM_TO_GROUP));
    }
}
