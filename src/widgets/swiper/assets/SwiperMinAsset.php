<?php
namespace lo\core\widgets\swiper\assets;


use yii\web\AssetBundle;

/**
 * Class SwiperMinAsset
 *
 * @package lo\core\widgets\swiper\assets
 */
class SwiperMinAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/swiper/dist';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/swiper.min.js'
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'css/swiper.css',
    ];

}
