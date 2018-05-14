<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */

echo "<?php\n";
if (!empty($namespace)) {
    echo "\nnamespace {$namespace};\n";
}
?>
/**
* Class <?= $className . "\n" ?>
*/
class <?= $className ?> extends Migration
{
    public function up()
    {
        $this->createTable($this->tn(self::TBL), [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'author_id' => $this->integer()->notNull(),
            'updater_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),

            'name' => $this->string()->notNull(),

        ]);

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