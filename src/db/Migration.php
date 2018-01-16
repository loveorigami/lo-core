<?php

namespace lo\core\db;

use mdm\admin\components\Configs;
use yii\db\ColumnSchemaBuilder;
use yii\helpers\Console;

/**
 * Custom migration which makes sure InnoDB with UTF-8 is preferred when using MySQL.
 */
class Migration extends \yii\db\Migration
{
    const ROW_COMPACT = 'COMPACT';
    const ROW_REDUNDANT = 'REDUNDANT';
    const ROW_DYNAMIC = 'DYNAMIC';
    const ROW_COMPRESSED = 'COMPRESSED';
    /**
     * @inheritdoc
     */
    public $tableGroup;
    public $rowFormat = self::ROW_COMPACT;

    public function createTable($table, $columns, $options = null)
    {
        if ($options === null && $this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB ROW_FORMAT = ' . $this->rowFormat;
        }
        parent::createTable($table, $columns, $options);
    }

    /**
     * @param $string
     * @return bool|int
     */
    protected function stderr($string)
    {
        if (Console::streamSupportsAnsiColors(\STDOUT)) {
            $string = Console::ansiFormat("    Error: " . $string, [Console::FG_RED]);
        }
        return fwrite(\STDERR, $string);
    }

    /**
     * [
     * 'id' => $this->primaryKey(),
     * 'name' => $this->string(128)->notNull(),
     * 'parent' => $this->integer(),
     * 'route' => $this->string(),
     * 'order' => $this->integer(),
     * 'data' => $this->binary(),
     * ]
     * @return string
     */
    protected function menuTable()
    {
        return Configs::instance()->menuTable;
    }

    /**
     * @param $name
     * @return false|null|string
     */
    protected function getMenuIdByName($name)
    {
        $tbl = $this->menuTable();
        $query = "SELECT * from $tbl WHERE `name` = '$name'";
        return $this->db->createCommand($query)->queryScalar();
    }

    /**
     * @param $name
     * @return false|null|string
     */
    protected function delMenuIdByName($name)
    {
        return $this->db->createCommand()->delete($this->menuTable(),[
            'name' => $name
        ])->execute();
    }

    /**
     * Real table name builder
     * @param string $name table name
     * @return string
     */
    protected function tn($name)
    {
        if (!$this->tableGroup) {
            return '{{%' . $name . '}}';
        }
        return '{{%' . $this->tableGroup . '__' . $name . '}}';
    }

    /**
     * Foreign key relation names generator
     * @param string $table1 first table in relation
     * @param string $table2 second table in relation
     * @return string
     */
    protected function fk($table1, $table2)
    {
        if (!$this->tableGroup) {
            return 'fk_' . $table1 . '_' . $table2;
        }
        return 'fk_' . $this->tableGroup . '__' . $table1 . '_' . $table2;
    }

    /**
     * @param $table
     * @param $column
     * @return string
     */
    protected function idx($table, $column)
    {
        return 'idx_' . $table . '_' . $column;
    }


    /**
     * Creates a smallint column.
     * @param int $length column size or precision definition.
     * This parameter will be ignored if not supported by the DBMS.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     * @since 2.0.6
     */
    public function tinyInteger($length = null)
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('tinyint', $length);
    }

    /**
     * Creates a medium text column.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function mediumText()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('mediumtext');
    }

    /**
     * Creates a long text column.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function longText()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext');
    }

    /**
     * Creates a tiny text column.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function tinyText()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('tinytext');
    }

}