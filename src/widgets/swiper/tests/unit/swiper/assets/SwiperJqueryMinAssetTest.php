<?php
namespace lo\core\widgets\swiper\tests\unit\swiper\assets;


use lo\core\widgets\swiper\assets\SwiperJqueryMinAsset;
use lo\core\widgets\swiper\tests\unit\BaseTestCase;

class SwiperJqueryMinAssetBaseTest extends BaseTestCase
{

    public function testMain()
    {
        SwiperJqueryMinAsset::register( \Yii::$app->getView() );
    }

}
