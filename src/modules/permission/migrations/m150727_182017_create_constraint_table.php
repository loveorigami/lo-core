<?php

namespace lo\core\modules\permission\migrations;

class m150727_182017_create_constraint_table extends Migration
{
    public function up()
    {
        $this->createTable($this->tn(self::TBL_CONSTRAINT), [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(1)->defaultValue(0),
            'author_id' => $this->integer()->notNull()->defaultValue(1),
            'updater_id' => $this->integer()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'role' => $this->string(64)->notNull(), // as in RBAC table
            'model' => $this->string()->notNull(),
            'constraint' => $this->string(),
            'forbidden_attrs' => $this->text(),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tn(self::TBL_CONSTRAINT));
    }
}
