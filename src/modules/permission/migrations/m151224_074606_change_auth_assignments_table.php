<?php

namespace lo\core\modules\permission\migrations;


class m151224_074606_change_auth_assignments_table extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%auth_assignment}}', 'user_id', $this->integer()->notNull());
        $this->createIndex('{{%idx-auth_assignments-user_id}}', '{{%auth_assignment}}', 'user_id');
        $this->addForeignKey('{{%fk-auth_assignments-user_id}}', '{{%auth_assignment}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk-auth_assignments-user_id}}', '{{%auth_assignment}}');
        $this->dropIndex('{{%idx-auth_assignments-user_id}}', '{{%auth_assignment}}');
        $this->alterColumn('{{%auth_assignment}}', 'user_id', $this->string(64)->notNull());
    }
}
