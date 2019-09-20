<?php
/**
 * @var \yii\web\View $this
 */
use lo\core\widgets\swiper\Swiper;

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
        Swiper::BEHAVIOUR_NEXT_BUTTON,
        Swiper::BEHAVIOUR_PREV_BUTTON,
        Swiper::BEHAVIOUR_RTL
    ],
    'pluginOptions' => [
        Swiper::OPTION_PAGINATION_CLICKABLE => true,
    ]
] );
