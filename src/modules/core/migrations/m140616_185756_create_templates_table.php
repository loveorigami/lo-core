<?php

namespace lo\core\modules\core\migrations;

class m140616_185756_create_templates_table extends Migration
{
    public function up()
    {
        $this->createTable($this->tn(self::TBL_TEMPLATE), [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(1)->defaultValue(0),
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

        $this->insert($this->tn(self::TBL_TEMPLATE), [
            'author_id' => 1,
            'updater_id' => 1,
            'created_at' => time(),
            'updated_at' => time(),
            'name' => 'Demo',
            'layout' => '//main',
            'text' => 'Demo template',
        ]);

    }

    public function down()
    {
        $this->dropTable($this->tn(self::TBL_TEMPLATE));
    }
}
