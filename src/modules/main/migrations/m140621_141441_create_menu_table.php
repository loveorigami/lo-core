<?php

namespace lo\core\modules\main\migrations;

class m140621_141441_create_menu_table extends Migration
{
    public function up()
    {
        $this->createTable($this->tn(self::TBL_MENU), [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(1)->defaultValue(0),
            'author_id' => $this->integer()->notNull(),
            'updater_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),

            'root' => $this->integer()->notNull()->defaultValue(0),
            'lft' => $this->integer()->notNull()->defaultValue(0),
            'rgt' => $this->integer()->notNull()->defaultValue(0),
            'level' => $this->integer()->notNull()->defaultValue(0),

            'name' => $this->string(),
            'code' => $this->string(),
            'link' => $this->string(),
            'target' => $this->string()->notNull()->defaultValue('_self'),
            'class' => $this->string(),
        ]);

        $this->insert($this->tn(self::TBL_MENU), [
            'author_id' => 1,
            'updater_id' => 1,
            'created_at' => time(),
            'updated_at' => time(),
            'root' => 1,
            'lft' => 1,
            'rgt' => 6,
            'level' => 1,
        ]);

        $this->insert($this->tn(self::TBL_MENU), [
            'author_id' => 1,
            'updater_id' => 1,
            'created_at' => time(),
            'updated_at' => time(),
            'root' => 1,
            'lft' => 2,
            'rgt' => 5,
            'level' => 2,
            'name' => 'Главное меню',
            'code' => 'main',
        ]);

        $this->insert($this->tn(self::TBL_MENU), [
            'author_id' => 1,
            'updater_id' => 1,
            'created_at' => time(),
            'updated_at' => time(),
            'root' => 1,
            'lft' => 3,
            'rgt' => 4,
            'level' => 3,
            'name' => 'Главная',
            'code' => '/',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->tn(self::TBL_MENU));
    }
}
