<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace lo\core\modules\i18n\migrations;

/**
 * Initializes i18n messages tables.
 * @since 2.0.7
 */
class m150207_210500_i18n_init extends Migration
{
    public function up()
    {
        $this->createTable($this->tn(self::TBL_SOURCE), [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->text(),
        ]);

        $this->createTable($this->tn(self::TBL_MSG), [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text(),
        ]);

        $this->addPrimaryKey('pk_message_id_language', $this->tn(self::TBL_MSG), ['id', 'language']);

        $this->addForeignKey(
            $this->fk(self::TBL_MSG, self::TBL_SOURCE),
            $this->tn(self::TBL_MSG), 'id',
            $this->tn(self::TBL_SOURCE), 'id',
            'CASCADE', 'RESTRICT'
        );

        $this->createIndex($this->idx(self::TBL_SOURCE, 'category'), $this->tn(self::TBL_SOURCE), 'category');
        $this->createIndex('idx_message_language', $this->tn(self::TBL_MSG), 'language');
    }

    public function down()
    {
        $this->dropForeignKey(
            $this->fk(self::TBL_MSG, self::TBL_SOURCE),
            $this->tn(self::TBL_MSG)
        );

        $this->dropTable($this->tn(self::TBL_MSG));
        $this->dropTable($this->tn(self::TBL_SOURCE));
    }
}
