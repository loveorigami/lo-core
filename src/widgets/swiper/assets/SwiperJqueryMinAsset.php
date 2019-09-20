<?php
namespace lo\core\widgets\swiper\assets;


use yii\web\AssetBundle;

/**
 * Class SwiperJqueryMinAsset
 *
 * @package lo\core\widgets\swiper\assets
 */
class SwiperJqueryMinAsset extends AssetBundle
{

    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/swiper/dist';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/swiper.jquery.min.js'
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'css/swiper.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
