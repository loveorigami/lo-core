<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace lo\core\modules\settings\migrations;

/**
 * Class m150207_210501_settings_init
 * @package lo\core\modules\settings\migrations
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class m150207_210501_settings_init extends Migration
{
    public function up()
    {
        $this->createTable($this->tn(self::TBL_ITEM), [
            'key' => $this->string(128)->notNull(),
            'value' => $this->text()->notNull(),
            'comment' => $this->text(),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer()
        ]);

        $this->addPrimaryKey('pk_key_storage_item_key', $this->tn(self::TBL_ITEM), 'key');
        $this->createIndex($this->idx(self::TBL_ITEM, 'key'), $this->tn(self::TBL_ITEM), 'key', true);
    }

    public function down()
    {
        $this->dropTable($this->tn(self::TBL_ITEM));
    }
}
