<?php
namespace lo\core\modules\core\migrations;

use lo\core\db\Migration;

class m150414_195800_timeline_event extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%timeline_event}}', [
            'id' => $this->primaryKey(),
            'application' => $this->string(64)->notNull(),
            'category' => $this->string(64)->notNull(),
            'event' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer()->notNull()
        ], $tableOptions);
        $this->createIndex('idx_created_at', '{{%timeline_event}}', 'created_at');
        $this->batchInsert(
            '{{%timeline_event}}',
            ['application', 'category', 'event', 'data', 'created_at'],
            [
                ['frontend', 'user', 'signup', json_encode([
                    'username' => 'webmaster',
                    'id' => 1,
                    'created_at' => time(),
                    'role' => 'user'
                ]), time()],
                ['frontend', 'user', 'signup', json_encode([
                    'username' => 'manager',
                    'id' => 2,
                    'created_at' => time(),
                    'role' => 'manager'
                ]), time()],
                ['frontend', 'user', 'signup', json_encode([
                    'username' => 'user',
                    'id' => 3, 
                    'created_at' => time(),
                    'role' => 'user'
                ]), time()]
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%timeline_event}}');
    }
}