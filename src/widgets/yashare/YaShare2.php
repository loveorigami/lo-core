<?php

namespace lo\core\widgets\yashare;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class YaShare2 extends Widget
{
    /* not all: https://tech.yandex.ru/share/doc/dg/add-docpage/ */
    const YA_COLLECTIONS = 'collections';
    const VK = 'vkontakte';
    const OK = 'odnoklassniki';
    const FB = 'facebook';
    const GPLUS = 'gplus';
    const TWITTER = 'twitter';
    const TUMBLR = 'tumblr';
    const LINKEDIN = 'linkedin';
    const PINTEREST = 'pinterest';

    const VIBER = 'viber';
    const WHATSAPP = 'whatsapp';
    const SKYPE = 'skype';
    const TELEGRAM = 'telegram';

    public $limit = 5;

    public $title;
    public $description;
    public $urlImage;
    public $urlPage;

    public $lang = 'ru';

    public $soc = [
        self::VK,
        self::OK,
        self::FB,
        self::TWITTER,
        self::VIBER,

        self::GPLUS,
        self::TUMBLR,
        self::PINTEREST,

        self::WHATSAPP,
        self::TELEGRAM,
    ];

    public $jsOption = [];

    /**
     * @return string
     */
    public function run()
    {
        YaShare2Asset::register($this->getView());

        $js = [
            'theme' => [
                'services' => implode(',', $this->soc),
                'lang' => $this->lang,
                'limit' => $this->limit
            ]
        ];

        foreach ($this->soc as $soc) {
            if ($this->title) {
                $js['contentByService'][$soc]['title'] = $this->title;
            }
            if ($this->description) {
                $js['contentByService'][$soc]['description'] = $this->title;
            }
            if ($this->urlImage) {
                $js['contentByService'][$soc]['image'] = $this->urlImage;
            }
            if ($this->urlPage) {
                $js['contentByService'][$soc]['url'] = $this->urlPage;
            }
        }

        $this->jsOption = Json::encode(ArrayHelper::merge($js, $this->jsOption));
        $this->getView()->registerJs("var share{$this->getId()} = Ya.share2('{$this->getId()}', {$this->jsOption});");

        return $this->render('share', ['id' => $this->id]);
    }
}
