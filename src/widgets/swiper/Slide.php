<?php
namespace lo\core\widgets\swiper;

use lo\core\widgets\swiper\helpers\SwiperCssHelper;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * Slide is representation of each slide for Swiper widget.
 * Do not use it directly if you don't really know what
 * you are doing.
 *
 * @package lo\core\widgets\swiper
 */
class Slide extends BaseObject
{

    /**
     * @see \lo\core\widgets\swiper\Slide::$content
     */
    const CONTENT = 'content';
    /**
     * @see \lo\core\widgets\swiper\Slide::$background
     */
    const BACKGROUND = 'background';
    /**
     * @see \lo\core\widgets\swiper\Slide::$hash
     */
    const HASH = 'hash';

    /**
     * @var string content part, which will be applied in [[\yii\helpers\Html::tag()]].
     */
    public $content;

    /**
     * @var string the shorthand alias which will be converted
     *             to [[background-image:url({$background})]] and them
     *             merged into other [[$options]]
     */
    public $background;

    /**
     * @var string the shorthand alias, which will be moved to
     *             [[$options['data']['hash']]]
     */
    public $hash;

    /**
     * @var mixed[] options, which will be applied in [[\yii\helpers\Html::tag()]]
     *
     * @see \lo\core\widgets\swiper\Slide::$background
     * @see \lo\core\widgets\swiper\Slide::$hash
     */
    public $options = [ ];

    /**
     * @param string|mixed[] $config the configuration of [[\lo\core\widgets\swiper\Slide]]
     *                               You can create slide just from string
     *                               For example:
     *
     *                               ~~~
     *                                 $slide = new \lo\core\widgets\swiper\Slide('slide content');
     *                               ~~~
     *
     *
     *                               Also you can create slide from array or strings and
     *                               they will be merged into one string
     *                               For example:
     *
     *                               ~~~
     *                                $slide = new \lo\core\widgets\swiper\Slide([
     *                                    'content' => [
     *                                        '<h1>Title</h1>',
     *                                        '<h3>Subtitle</h3>',
     *                                        '<p>Main content</p>'
     *                                    ]
     *                                ]);
     *                               ~~~
     *
     * @see \lo\core\widgets\swiper\Slide::$background
     * @see \lo\core\widgets\swiper\Slide::$hash
     * @see \lo\core\widgets\swiper\Slide::$content
     */
    public function __construct( $config = [ ] )
    {

        $config = is_string( $config )
            ? [ self::CONTENT => $config ]
            : $config;

        $config[self::CONTENT] = ArrayHelper::getValue( $config, self::CONTENT, null );

        $config[self::CONTENT] = is_array( $config[self::CONTENT] )
            ? implode( '', $config[self::CONTENT] )
            : $config[self::CONTENT];

        parent::__construct( $config );
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->normalizeOptions();
    }

    /**
     * This function sets default values to
     * options for further usage
     */
    protected function normalizeOptions()
    {
        $this->options['data']  = ArrayHelper::getValue( $this->options, 'data', [ ] );
        $this->options['style'] = ArrayHelper::getValue( $this->options, 'style', '' );

        $this->options['data']['hash'] = $this->hash ?: ArrayHelper::getValue( $this->options['data'], 'hash', null );
        $this->hash                    = $this->hash ?: ArrayHelper::getValue( $this->options['data'], 'hash', null );

        if ($this->background) {

            $this->options['style'] = SwiperCssHelper::mergeStyleAndBackground(
                $this->background,
                ArrayHelper::getValue( $this->options, 'style', '' )
            );

        } elseif (ArrayHelper::getValue( $this->options, 'style' )) {

            $this->background = SwiperCssHelper::getBackgroundUrl(
                $this->options['style']
            );

        }

        $this->options         = array_filter( $this->options );
        $this->options['data'] = array_filter( $this->options['data'] );
    }

}
