<?php
namespace lo\core\modules\core\migrations;

class m150414_195800_timeline_event extends Migration
{
    public function up()
    {
        $this->createTable($this->tn(self::TBL_TIMELINE), [
            'id' => $this->primaryKey(),
            'application' => $this->string(64)->notNull(),
            'category' => $this->string(64)->notNull(),
            'event' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer()->notNull()
        ]);

        $this->createIndex('idx_created_at', $this->tn(self::TBL_TIMELINE), 'created_at');

        $this->batchInsert(
            $this->tn(self::TBL_TIMELINE),
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
        $this->dropTable($this->tn(self::TBL_TIMELINE));
    }
}