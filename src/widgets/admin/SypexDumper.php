<?php

namespace lo\core\widgets\admin;

use PDOException;
use Yii;
use yii\base\Widget;

class SypexDumper extends Widget
{
    public function run()
    {
        try {
            // ссылка до sypex dumper
            $sxdUrl = Yii::getAlias('@sxdUrl');
            // выводим iframe
            echo "<iframe src='$sxdUrl/' style=\"height: 468px; width: 586px; border: 0\"></iframe>";
        } catch (PDOException $error) {
            echo 'Error connect to MySQL: '.$error->getMessage();
        }
    }
}
