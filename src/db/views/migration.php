<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */

echo "<?php\n";
?>

use lo\core\db\Migration;

class <?= $className ?> extends Migration
{
    public $tableGroup = "module";

    const TBL = 'item';
    //const TBL_PARENT = 'cat';

    public function up()
    {
        $this->createTable($this->tn(self::TBL), [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(),
            'author_id' => $this->integer()->notNull(),
            'updater_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),

            'name' => $this->string()->notNull(),

        ]);

        $this->createIndex('idx_table_name_status', $this->tn(self::TBL), 'status');

/*
    $this->addForeignKey(
        $this->fk(self::TBL, self::TBL_PARENT),
        $this->tn(self::TBL), 'cat_id',
        $this->tn(self::TBL_PARENT), 'id',
        'RESTRICT', 'RESTRICT'
    );
*/
    }

    public function down()
    {
/*
        $this->dropForeignKey(
            $this->fk(self::TBL, self::TBL_PARENT),
            $this->tn(self::TBL)
        );
*/
        $this->dropTable($this->tn(self::TBL));
    }


}