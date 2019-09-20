<?php
namespace lo\core\widgets\swiper\tests\unit\swiper\assets;


use lo\core\widgets\swiper\assets\SwiperMinAsset;
use lo\core\widgets\swiper\tests\unit\BaseTestCase;

class SwiperMinAssetBaseTest extends BaseTestCase
{

    public function testMain()
    {
        SwiperMinAsset::register( \Yii::$app->getView() );
    }

}
