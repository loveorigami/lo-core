<?php
/**
 * @var \yii\web\View $this
 */
use lo\core\widgets\swiper\Swiper;
use yii\web\JsExpression;

echo Swiper::widget( [
    'items'         => [
        'Slide 1',
        'Slide 2',
        'Slide 3',
        'Slide 4',
        'Slide 5',
        'Slide 6',
        'Slide 7',
        'Slide 8',
        'Slide 9',
        'Slide 10',
    ],
    'behaviours'    => [
        Swiper::BEHAVIOUR_PAGINATION,
    ],
    'pluginOptions' => [
        Swiper::OPTION_PAGINATION_CLICKABLE     => true,
        Swiper::OPTION_PAGINATION_BULLET_RENDER => new JsExpression( <<<JS
(function ( index, className ) {
    return '<span class="' + className + '">' + (index + 1) + '</span>';
})
JS
        )
    ]
] );
