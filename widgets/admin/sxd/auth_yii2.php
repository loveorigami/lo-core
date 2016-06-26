<?php

// Composer
require(__DIR__ . '/../../../../vendor/autoload.php');
// Environment
require(__DIR__ . '/../../../../common/env.php');
// Yii2
require(__DIR__ . '/../../../../vendor/yiisoft/yii2/Yii.php');
// Bootstrap application
require(__DIR__ . '/../../../../common/config/bootstrap.php');
require(__DIR__ . '/../../../../backend/config/bootstrap.php');

$config = \yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../../../common/config/main.global.php'),
	require(__DIR__ . '/../../../../common/config/main.local.php'),
    require(__DIR__ . '/../../../../backend/config/main.global.php'),
	require(__DIR__ . '/../../../../backend/config/main.local.php')
);

$application = new \yii\web\Application($config);

function multiexplode($delimiters, $string)
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);

    return $launch;
}

$dsn = multiexplode(array(':', ';', '='), getenv('DB_DSN'));

if (\Yii::$app->user->can('root')) {
    $this->CFG['backup_path'] = \Yii::getAlias('@backend').'/_backup/';
    $this->CFG['my_host'] = $dsn[2];
    $this->CFG['my_port'] = $dsn[4];
    $this->CFG['my_user'] = getenv('DB_USERNAME');
    $this->CFG['my_pass'] = getenv('DB_PASSWORD');
    $this->CFG['my_db'] = $dsn[6];

    $auth = true;
};
