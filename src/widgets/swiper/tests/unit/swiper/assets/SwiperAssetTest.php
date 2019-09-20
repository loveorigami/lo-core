<?php
namespace lo\core\widgets\swiper\tests\unit\swiper\assets;


use lo\core\widgets\swiper\assets\SwiperAsset;
use lo\core\widgets\swiper\tests\unit\BaseTestCase;

class SwiperAssetBaseTest extends BaseTestCase
{
    public function testMain()
    {
        SwiperAsset::register( \Yii::$app->getView() );
    }
}
