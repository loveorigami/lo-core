<?php

namespace lo\core\modules\main\migrations;

class m140623_175657_create_includes_table extends Migration
{

    public function safeUp()
    {
        $this->createTable($this->tn(self::TBL_INC_ITEM), [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(1)->defaultValue(0),
            'author_id' => $this->integer()->notNull(),
            'updater_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),

            'name' => $this->string(),
            'code' => $this->string()->unique(),
            "text" => $this->text(),
            "file" => $this->string(),
        ]);

        $this->insert($this->tn(self::TBL_INC_ITEM), [
            'author_id' => 1,
            'updater_id' => 1,
            'created_at' => time(),
            'updated_at' => time(),
            'name' => 'Demo область',
            'code' => 'demo',
            'text' => 'Демонстрационная включаемая область',
        ]);

        $this->createIndex($this->idx(self::TBL_INC_ITEM, 'code'), $this->tn(self::TBL_INC_ITEM), 'code');
    }

    public function safeDown()
    {
        $this->dropTable($this->tn(self::TBL_INC_ITEM));
    }
}
