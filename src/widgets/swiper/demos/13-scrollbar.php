<?php
/**
 * @var \yii\web\View $this
 */
use lo\core\widgets\swiper\Swiper;

echo Swiper::widget( [
    'items'            => [
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
    'behaviours'       => [
        Swiper::BEHAVIOUR_SCROLLBAR
    ],
    'scrollbarOptions' => [
        'class' => 'my-custom-scrollbar-class'
    ],
    'pluginOptions'    => [
        Swiper::OPTION_SCROLLBAR_HIDE       => true,
        Swiper::OPTION_SLIDES_PER_VIEW      => Swiper::SLIDES_PER_VIEW_AUTO,
        Swiper::OPTION_CENTERED_SLIDES      => true,
        Swiper::OPTION_PAGINATION_CLICKABLE => true,
        Swiper::OPTION_SPACE_BETWEEN        => 30,
        Swiper::OPTION_GRAB_CURSOR          => true
    ]
] );
