<?php
namespace lo\core\widgets\swiper\assets;

use yii\web\AssetBundle;

/**
 * Class SwiperAsset
 *
 * @package lo\core\widgets\swiper\assets
 */
class SwiperAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/swiper/dist';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/swiper.js'
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'css/swiper.css',
    ];

}
