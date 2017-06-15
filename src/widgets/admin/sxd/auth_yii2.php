<?php
$dir = getenv('PHP_DOCUMENT_ROOT');
// Composer
require("$dir/vendor/autoload.php");
// Yii2
require("$dir/vendor/yiisoft/yii2/Yii.php");
// Bootstrap application
require("$dir/common/config/bootstrap.php");
require("$dir/backend/config/bootstrap.php");

$config = \yii\helpers\ArrayHelper::merge(
    require("$dir/common/config/main.php"),
    require("$dir/common/config/main-local.php"),
    require("$dir/backend/config/main.php"),
    require("$dir/backend/config/main-local.php")
);

$application = new \yii\web\Application($config);
$db = $application->getDb();

function multiexplode($delimiters, $string)
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);

    return $launch;
}

$dsn = multiexplode(array(':', ';', '='), $db->dsn);

if (\Yii::$app->user->can('root')) {
    $this->CFG['backup_path'] = \Yii::getAlias('@backend') . '/_backup/';
    $this->CFG['my_host'] = $dsn[2];
    $this->CFG['my_port'] = $dsn[4];
    $this->CFG['my_user'] = $db->username;
    $this->CFG['my_pass'] = $db->password;
    $this->CFG['my_db'] = $dsn[6];

    $auth = true;
};