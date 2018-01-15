<?php
/**
 * @var array $models массив моделей меню
 * @var int $parentLevel уровень вложенности родительского пункта меню
 * @var int $level уровень начала вложенности
 * @var array $options массив html атрибутов корневого Ul
 * @var array $sub_options массив html атрибутов дочернего Ul
 * @var string $actClass имя класса активного пункта меню
 */

use yii\helpers\Html;

echo Html::beginTag('ul', $options) . "\n";

foreach ($models AS $model) {

    $o = [];
    $oa = ["target" => $model->target];

    // Для всех кроме первой итерации
    if (isset($level)) {
        if ($model->level == $level) {
            echo "</li>\n";
        } elseif ($model->level > $level) {
            echo Html::beginTag('ul', $sub_options);
        } else {
            echo str_repeat("</li>\n</ul>\n", $level - $model->level);
            echo "</li>\n";
        }
    }

    if ($model->isAct()){
        Html::addCssClass($oa, $actClass);
    }

    if (!empty($model->class)){
        Html::addCssClass($o, $model->class);
    }

/*
    $username = isset(Yii::$app->user->identity->username) ?: '';
    $tpl['search'] = ['/{username}/'];
    $tpl['replace'] = [$username];
    $name = preg_replace($tpl['search'], $tpl['replace'], $model->name);
*/

    $link = ($model->link == '#') ? '#' : [$model->link];

    if($model->link == '/user/security/logout'){
      $oa['data-method'] = 'post';
    }

    echo Html::beginTag('li', $o) . Html::a($model->name, $link, $oa);

    $level = $model->level;
}

echo str_repeat("</li>\n</ul>\n", $level - $parentLevel);