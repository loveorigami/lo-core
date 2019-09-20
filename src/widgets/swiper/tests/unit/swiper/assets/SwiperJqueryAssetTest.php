<?php
namespace lo\core\widgets\swiper\tests\unit\swiper\assets;


use lo\core\widgets\swiper\assets\SwiperJqueryAsset;
use lo\core\widgets\swiper\tests\unit\BaseTestCase;

class SwiperJqueryAssetBaseTest extends BaseTestCase
{

    public function testMain()
    {
        SwiperJqueryAsset::register( \Yii::$app->getView() );
    }

}
