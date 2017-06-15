<?php

namespace lo\core\widgets\admin;

use PDOException;
use Yii;
use PDO;
use yii\base\Widget;

class SypexDumper extends Widget
{
    public function run()
    {
        // функция копирования директории
        function full_copy($source, $target)
        {
            if (is_dir($source)) {
                @mkdir($target);
                $dir = dir($source);
                while (false !== ($entry = $dir->read())) {
                    if ($entry == '.' || $entry == '..') {
                        continue;
                    }
                    full_copy("$source/$entry", "$target/$entry");
                }
                $dir->close();
            } else {
                copy($source, $target);
            }
        }

        // проверяем подключение к MySQL
        try {
            // путь до директории, в которую копируем sypex dumper
            $sxdPath = Yii::getAlias('@sxd');

            // проверяем существование директории, копируем, если директории нет
            if (!is_dir($sxdPath)) {
                full_copy(__DIR__.'/sxd', $sxdPath);
            };

            // ссылка до sypex dumper
            $sxdUrl = Yii::getAlias('@sxdUrl');
            // выводим iframe
            echo "<iframe src=\"$sxdUrl\" style=\"height: 468px; width: 586px; border: 0\"></iframe>";
        } catch (PDOException $error) {
            echo 'Error connect to MySQL: '.$error->getMessage();
        }
    }
}
