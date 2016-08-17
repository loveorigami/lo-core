<?php

use yii\db\Schema;

class m150727_182017_create_constraint_table extends \yii\db\Migration
{

    public $tableName = "auth_constraint";

    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%$this->tableName}}", [
            'id' => Schema::TYPE_PK,
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'author_id' => Schema::TYPE_INTEGER,
            'updater_id' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'role' => Schema::TYPE_STRING . '(64) NOT NULL', // as in RBAC table
            'model' => Schema::TYPE_STRING . ' NOT NULL',
            'constraint' => Schema::TYPE_STRING,
            'forbidden_attrs' => Schema::TYPE_TEXT,
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable("{{%$this->tableName}}");
    }
}
