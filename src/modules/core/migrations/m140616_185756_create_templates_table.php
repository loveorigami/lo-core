<?php

namespace lo\core\modules\core\migrations;

use lo\core\db\Migration;

class m140616_185756_create_templates_table extends Migration
{
	public $tableName = "{{%core__templates}}";

    public function up()
    {

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'status' => 'tinyint(1) NOT NULL DEFAULT 0',
            'author_id' => $this->integer()->notNull(),
            'updater_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
			
            'name' => $this->string(),
            'layout' => $this->string(),
            'cond' => $this->string(),
            'text' => $this->string(),
            'cond_type' => $this->integer()->notNull()->defaultValue(0),
			'pos' => $this->integer()->notNull()->defaultValue(100),
        ]);

        $this->insert($this->tableName, [
            'author_id' => 1,
            'name' => 'Demo',
            'layout' => '//main',
            'text' => 'Demo template',
        ]);

    }

    public function down()
    {

        $this->dropTable($this->tableName);

    }
}
