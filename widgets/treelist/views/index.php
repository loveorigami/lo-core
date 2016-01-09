<?php
/**
 * @var array $models массив моделей меню
 * @var int $parentLevel уровень вложенности родительского пункта меню
 * @var array $options массив html атрибутов корневого Ul
 * @var string $actClass имя класса активного пункта меню
 * @var closure $urlCreate функция для содания url
 * @var string $labelAttr имя выводимого атрибута
 */

use yii\helpers\Html;

echo Html::beginTag('ul', $options) . "\n";

foreach ($models AS $model) {

    // Для всех кроме первой итерации
    if (isset($level)) {

        if ($model->level == $level) {
            Html::endTag('li') . "\n";
        } elseif ($model->level > $level) {
            echo Html::beginTag('ul') . "\n";
        } else {
            echo str_repeat(
                Html::endTag('li') . "\n".
                Html::endTag('ul') . "\n",
                $level - $model->level
            );
            Html::endTag('li') . "\n";
        }
    }

    $link = "";
    $io =  ($parentLevel + 1 == $model->level) ? $itemOptions : [];

    if (is_callable($urlCreate))
        $link = $urlCreate($model);


    if ($this->context->isAct($link))
        Html::addCssClass($io, $actClass);

    echo Html::beginTag('li', $io) . Html::a($model->$labelAttr, $link);

    $level = $model->level;

}

echo str_repeat(
    Html::endTag('li') . "\n".
    Html::endTag('ul') . "\n",
    $level - $parentLevel
);


