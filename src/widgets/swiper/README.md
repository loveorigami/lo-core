# Yii2-swiper

Yii2 widget for Swiper slider. See more here: [https://github.com/nolimits4web/Swiper/](https://github.com/nolimits4web/Swiper).

You can find examples in [demos](demos) folder.

Also you can read [documentation](docs).

## Installation
 
You can get this extension through [Composer](https://getcomposer.org/download/).
 
Either run in terminal
 
```Shell
$ php composer.phar require "romka-chev/yii2-swiper" "*"
```
 
or add
 
```JSON
"romka-chev/yii2-swiper" : "*"
```
 
to the *require* section of your application's ```composer.json``` file.
    
###Usage

```PHP
 
<?php
/**
 * @var \yii\web\View $this
 */
use lo\core\widgets\swiper\Swiper;

echo Swiper::widget( [
  'items'         => [
    [ 'background' => 'http://lorempixel.com/600/600/nature/1' ],
    [ 'background' => 'http://lorempixel.com/600/600/nature/2' ],
    [ 'background' => 'http://lorempixel.com/600/600/nature/3' ],
    [ 'background' => 'http://lorempixel.com/600/600/nature/4' ],
    [ 'background' => 'http://lorempixel.com/600/600/nature/5' ]
  ],
  'behaviours'    => [
    'pagination'
  ],
  'pluginOptions' => [
    'grabCursor'     => true,
    'centeredSlides' => true,
    'slidesPerView'  => 'auto',
    'effect'         => 'coverflow',
    'coverflow'      => [
        'rotate'       => 50,
        'stretch'      => 0,
        'depth'        => 100,
        'modifier'     => 1,
        'slideShadows' => true
    ]
  ]
] );
 
```