<?php

namespace lo\core\modules\main\migrations;

class m150219_073021_create_include_groups_table extends Migration
{

    public function safeUp()
    {
        $this->createTable($this->tn(self::TBL_INC_GROUP), [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(1)->defaultValue(0),
            'author_id' => $this->integer()->notNull(),
            'updater_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),

            'name' => $this->string(),
            'code' => $this->string()->unique(),
            'cond' => $this->string(),
            'cond_type' => $this->integer()->notNull()->defaultValue(0),
            'pos' => $this->integer()->notNull()->defaultValue(500),
        ]);

        $this->createIndex($this->idx(self::TBL_INC_GROUP, 'code'), $this->tn(self::TBL_INC_GROUP), 'code');
    }

    public function safeDown()
    {
        $this->dropTable($this->tn(self::TBL_INC_GROUP));
    }
}
