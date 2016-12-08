<?php

namespace lo\core\helpers;

use yii\bootstrap\Html;

/**
 * Class BS
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class BS extends Html
{
    /**
     * Bootstrap CSS helpers
     */
    const FLOAT_LEFT         = 'pull-left';
    const FLOAT_RIGHT        = 'pull-right';
    const FLOAT_CENTER       = 'center-block';
    const NAVBAR_FLOAT_LEFT  = 'navbar-left';
    const NAVBAR_FLOAT_RIGHT = 'navbar-right';
    const CLEAR_FLOAT        = 'clearfix';
    const SHOW               = 'show';
    const HIDDEN             = 'hidden';
    const INVISIBLE          = 'invisible';
    const SCREEN_READER      = 'sr-only';
    const IMAGE_REPLACER     = 'text-hide';
    
    /**
     * Bootstrap size modifier suffixes
     */
    const SIZE_TINY   = 'xs';
    const SIZE_SMALL  = 'sm';
    const SIZE_MEDIUM = 'md';
    const SIZE_LARGE  = 'lg';
    
    /**
     * Bootstrap color modifier classes
     */
    const TYPE_DEFAULT = 'default';
    const TYPE_PRIMARY = 'primary';
    const TYPE_SUCCESS = 'success';
    const TYPE_INFO    = 'info';
    const TYPE_WARNING = 'warning';
    const TYPE_DANGER  = 'danger';
    
    /**
     * Generates an icon.
     *
     * @param string $icon    the bootstrap icon name without prefix (e.g. 'plus', 'pencil', 'trash')
     * @param array  $options html options for the icon container
     * @param string $prefix  the css class prefix - defaults to 'glyphicon glyphicon-'
     * @param string $tag     the icon container tag (usually 'span' or 'i') - defaults to 'span'
     *
     * Example(s):
     * ~~~
     * echo BS::icon('pencil');
     * echo BS::icon('trash', ['style' => 'color: red; font-size: 2em']);
     * echo BS::icon('plus', ['class' => 'text-success']);
     * ~~~
     * @see http://getbootstrap.com/components/#glyphicons
     * @return string
     */
    public static function icon($icon, $options = [], $prefix = 'glyphicon glyphicon-', $tag = 'span')
    {
        $class = isset($options['class']) ? ' '.$options['class'] : '';
        $options['class'] = $prefix.$icon.$class;
        return static::tag($tag, '', $options);
    }
    
    /**
     * Generates a label.
     *
     * @param string $content the label content
     * @param string $type    the bootstrap label type - defaults to 'default'
     *                        - is one of 'default, 'primary', 'success', 'info', 'danger', 'warning'
     * @param array  $options html options for the label container
     * @param string $prefix  the css class prefix - defaults to 'label label-'
     * @param string $tag     the label container tag - defaults to 'span'
     *
     * Example(s):
     * ~~~
     * echo BS::label('Default');
     * echo BS::label('Primary', BS::TYPE_PRIMARY);
     * echo BS::label('Success', BS::TYPE_SUCCESS);
     * ~~~
     *
     * @see http://getbootstrap.com/components/#labels
     *
     * @return string
     */
    public static function label($content, $type = '', $options = [], $prefix = 'label label-', $tag = 'span')
    {
        if (VarHelper::isEmpty($type)) {
            $type = self::TYPE_DEFAULT;
        }
        $class = isset($options['class']) ? ' '.$options['class'] : '';
        $options['class'] = $prefix.$type.$class;
        return static::tag($tag, $content, $options);
    }
    
    /**
     * Generates a badge.
     *
     * @param string $content the badge content
     * @param array  $options html options for the badge container
     * @param string $tag     the badge container tag - defaults to 'span'
     *
     * Example(s):
     * ~~~
     * echo BS::badge('1');
     * ~~~
     * @see http://getbootstrap.com/components/#badges
     * @return string
     */
    public static function badge($content, $options = [], $tag = 'span')
    {
        static::addCssClass($options, 'badge');
        return static::tag($tag, $content, $options);
    }
    
    /**
     * Generates a list group. Flexible and powerful component for displaying not only
     * simple lists of elements, but complex ones with custom content.
     *
     * @param array  $items   the list group items - each element in the array must contain these keys:
     *                        - param mixed $content the list item content
     *                        - when passed as a string, it will display this directly as a raw content
     *                        - when passed as an array, it requires these keys
     *                        - param string $heading the content heading
     *                        - param string $body the content body
     *                        - param string $url the url for linking the list item content (optional)
     *                        - param string $badge a badge component to be displayed for this list item (optional)
     *                        - param boolean $active to highlight the item as active (applicable only if $url is
     *                        passed) - default false
     * @param array  $options html options for the list group container
     * @param string $tag     the list group container tag - defaults to 'div'
     * @param string $itemTag the list item container tag - defaults to 'div'
     *
     * Example(s):
     * ~~~
     * echo BS::listGroup([
     *    [
     *      'content' => 'Cras justo odio',
     *      'url' => '#',
     *      'badge' => '14',
     *      'active' => true
     *    ],
     *    [
     *      'content' => 'Dapibus ac facilisis in',
     *      'url' => '#',
     *      'badge' => '2'
     *    ],
     *    [
     *      'content' => 'Morbi leo risus',
     *      'url' => '#',
     *      'badge' => '1'
     *    ],
     * ]);
     *
     * echo BS::listGroup([
     *    [
     *      'content' => ['heading' => 'Heading 1', 'body' => 'Cras justo odio'],
     *      'url' => '#',
     *      'badge' => '14',
     *      'active' => true
     *    ],
     *    [
     *      'content' => ['heading' => 'Heading 2', 'body' => 'Dapibus ac facilisis in'],
     *      'url' => '#',
     *      'badge' => '2'
     *    ],
     *    [
     *      'content' => ['heading' => 'Heading 2', 'body' => 'Morbi leo risus'],
     *      'url' => '#',
     *      'badge' => '1'
     *    ],
     * ]);
     * ~~~
     *
     * @see http://getbootstrap.com/components/#list-group
     * @return string
     */
    public static function listGroup($items = [], $options = [], $tag = 'div', $itemTag = 'div')
    {
        static::addCssClass($options, 'list-group');
        $content = '';
        foreach ($items as $item) {
            $content .= static::generateListGroupItem($item, $itemTag)."\n";
        }
        return static::tag($tag, $content, $options);
    }
    
    /**
     * Processes and generates each list group item
     * @param array  $item the list item configuration
     * @param string $tag  the list item container tag
     * @return string
     */
    protected static function generateListGroupItem($item, $tag)
    {
        static::addCssClass($item['options'], 'list-group-item');
        /* Parse item content */
        $content = isset($item['content']) ? $item['content'] : '';
        if (is_array($content)) {
            $heading = isset($content['heading']) ? $content['heading'] : '';
            $body = isset($content['body']) ? $content['body'] : '';
            if (!VarHelper::isEmpty($heading)) {
                $heading = static::tag('h4', $heading, ['class' => 'list-group-item-heading']);
            }
            if (!VarHelper::isEmpty($body)) {
                $body = static::tag('p', $body, ['class' => 'list-group-item-text']);
            }
            $content = $heading."\n".$body;
        }
        /* Parse item badge component */
        $badge = isset($item['badge']) ? $item['badge'] : '';
        if (!VarHelper::isEmpty($badge)) {
            $content = static::badge($badge).$content;
        }
        /* Parse item url */
        $url = isset($item['url']) ? $item['url'] : '';
        if (!VarHelper::isEmpty($url)) {
            /* Parse if item is active */
            if (isset($item['active']) && $item['active']) {
                static::addCssClass($item['options'], 'active');
            }
            return static::a($content, $url, $item['options']);
        } else {
            return static::tag($tag, $content, $item['options']);
        }
    }
    
    /**
     * Generates a jumbotron - a lightweight, flexible component that can optionally
     * extend the entire viewport to showcase key content on your site.
     *
     * @param mixed   $content   the jumbotron content
     *                           - when passed as a string, it will display this directly as a raw content
     *                           - when passed as an array, it requires these keys
     *                           - param string $heading the jumbotron content title
     *                           - param string $body the jumbotron content body
     *                           - param array $buttons the jumbotron buttons
     *                           - param string $label the button label
     *                           - param string $icon the icon to place before the label
     *                           - param string $url the button url
     *                           - param string $type one of the color modifier constants - defaults to
     *                           self::TYPE_DEFAULT
     *                           - param string $size one of the size modifier constants
     *                           - param array $options the button html options
     * @param boolean $fullWidth whether this is a full width jumbotron without any corners - defaults to false
     * @param array   $options   html options for the jumbotron
     *
     * Example(s):
     * ~~~
     * echo BS::jumbotron(
     *      '<h1>Hello, world</h1><p>This is a simple jumbotron-style component for calling extra attention to
     *      featured content or information.</p>'
     * );
     * echo BS::jumbotron(
     *  [
     *      'heading' => 'Hello, world!',
     *      'body' => 'This is a simple jumbotron-style component for calling extra attention to featured content
     *      or
     *      information.'
     *    ]
     * );
     * echo BS::jumbotron([
     *      'heading' => 'Hello, world!',
     *      'body' => 'This is a simple jumbotron-style component for calling extra attention to featured content
     *      or
     *      information.',
     *      'buttons' => [
     *          [
     *              'label' => 'Learn More',
     *              'icon' => 'book',
     *              'url' => '#',
     *              'type' => BS::TYPE_PRIMARY,
     *              'size' => BS::LARGE
     *          ],
     *          [
     *              'label' => 'Contact Us',
     *              'icon' => 'phone',
     *              'url' => '#',
     *              'type' => BS::TYPE_DANGER,
     *              'size' => BS::LARGE
     *          ]
     *      ]
     * ]);
     * ~~~
     * @see http://getbootstrap.com/components/#jumbotron
     * @return string
     */
    public static function jumbotron($content = [], $fullWidth = false, $options = [])
    {
        static::addCssClass($options, 'jumbotron');
        if (is_string($content)) {
            $html = $content;
        } else {
            $html = isset($content['heading']) ? "<h1>".$content['heading']."</h1>\n" : '';
            $body = isset($content['body']) ? $content['body']."\n" : '';
            if (substr(preg_replace('/\s+/', '', $body), 0, 3) != '<p>') {
                $body = static::tag('p', $body);
            }
            $html .= $body;
            $buttons = '';
            if (isset($content['buttons'])) {
                foreach ($content['buttons'] as $btn) {
                    $label = (isset($btn['icon']) ? static::icon($btn['icon']).' ' : '').(isset($btn['label']) ? $btn['label'] : '');
                    $url = isset($btn['url']) ? $btn['url'] : '#';
                    $btnOptions = isset($btn['options']) ? $btn['options'] : [];
                    $class = 'btn'.(isset($btn['type']) ? ' btn-'.$btn['type'] : ' btn-'.self::TYPE_DEFAULT);
                    $class .= isset($btn['size']) ? ' btn-'.$btn['size'] : '';
                    static::addCssClass($btnOptions, $class);
                    $buttons .= static::a($label, $url, $btnOptions)." ";
                }
            }
            $html .= static::tag('p', $buttons);
        }
        if ($fullWidth) {
            return static::tag('div', static::tag('div', $html, ['class' => 'container']), $options);
        } else {
            return static::tag('div', $html, $options);
        }
    }

    /**
     * Generates a panel for boxing content.
     *
     * @param array  $content the panel content consisting of these components:
     *                        - param string $preHeading raw content that will be placed before $heading (optional)
     *                        - param string $heading the panel box heading (optional)
     *                        - param string $preBody raw content that will be placed before $body (optional)
     *                        - param string $body the panel body content - this will be wrapped in a "panel-body"
     *                        container (optional)
     *                        - param string $postBody raw content that will be placed after $body (optional)
     *                        - param string $footer the panel box footer (optional)
     *                        - param string $postFooter raw content that will be placed after $footer (optional)
     *                        - param boolean $headingTitle whether to pre-style heading content with a
     *                        '.panel-title' class.
     *                        - param boolean $footerTitle whether to pre-style footer content with a
     *                        '.panel-title'
     *                        class.
     * @param string $type    the panel type one of the color modifier constants - defaults to 'default'
     *                        - TYPE_DEFAULT = 'default'
     *                        - TYPE_PRIMARY = 'primary'
     *                        - TYPE_SUCCESS = 'success'
     *                        - TYPE_INFO    = 'info'
     *                        - TYPE_WARNING = 'warning'
     *                        - TYPE_DANGER  = 'danger'
     * @param array  $options html options for the panel container
     *
     * Example(s):
     * ~~~
     * echo BS::panel(
     *    ['heading' => 'Panel Heading', 'body' => 'Panel Content'],
     *    BS::TYPE_SUCCESS
     * );
     * echo BS::panel(
     *    [
     *      'heading' => 'Panel Heading',
     *      'body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ' .
     *          'sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
     *      'postBody' => BS::listGroup([
     *          [
     *              'content' => 'Cras justo odio',
     *              'url' => '#',
     *              'badge' => '14'
     *          ],
     *          [
     *              'content' => 'Dapibus ac facilisis in',
     *              'url' => '#',
     *              'badge' => '2'
     *          ],
     *          [
     *              'content' => 'Morbi leo risus',
     *              'url' => '#',
     *              'badge' => '1'
     *          ],
     *      ], [], 'ul', 'li'),
     *      'footer'=> 'Panel Footer',
     *      'headingTitle' => true,
     *      'footerTitle' => true,
     *  ]
     * );
     * ~~~
     * @param array  $options html options for the panel
     * @see http://getbootstrap.com/components/#panels
     * @return string
     */
    public static function panel($content = [], $type = 'default', $options = [])
    {
        if (!is_array($content)) {
            return '';
        } else {
            static::addCssClass($options, 'panel panel-'.$type);
            $panel = (!VarHelper::isEmpty($content['preHeading'])) ? $content['preHeading']."\n" : '';
            $panel .= static::generatePanelTitle($content, 'heading');
            $panel .= (!VarHelper::isEmpty($content['preBody'])) ? $content['preBody']."\n" : '';
            $panel .= (!VarHelper::isEmpty($content['body'])) ? static::tag('div', $content['body'],
                    ['class' => 'panel-body'])."\n" : '';
            $panel .= (!VarHelper::isEmpty($content['postBody'])) ? $content['postBody']."\n" : '';
            $panel .= static::generatePanelTitle($content, 'footer');
            $panel .= (!VarHelper::isEmpty($content['postFooter'])) ? $content['postFooter']."\n" : '';
            return static::tag('div', $panel, $options);
        }
    }

    /**
     * Generates panel title for heading and footer.
     * @param array  $content the panel content components.
     * @param string $type    whether 'heading' or 'footer'
     * @return string
     */
    protected static function generatePanelTitle($content, $type)
    {
        if (!VarHelper::isEmpty($content[$type])) {
            $title = $content[$type];
            if (isset($content["{$type}Title"]) && $content["{$type}Title"]) {
                $title = static::tag("h3", $title, ["class" => "panel-title"]);
            }
            return static::tag("div", $title, ["class" => "panel-{$type}"])."\n";
        } else {
            return '';
        }
    }

    /**
     * Generates a page header.
     *
     * @param string $title    the title to be shown
     * @param string $subTitle the subtitle to be shown as subtext within the title
     * @param array  $options  html options for the page header
     *
     * Example(s):
     * ~~~
     * echo BS::pageHeader(
     *    'Example page header',
     *    'Subtext for header'
     * );
     * ~~~
     * @see http://getbootstrap.com/components/#page-header
     * @return string
     */
    public static function pageHeader($title, $subTitle = '', $options = [])
    {
        static::addCssClass($options, 'page-header');
        if (!VarHelper::isEmpty($subTitle)) {
            $title = "<h1>{$title} <small>{$subTitle}</small></h1>";
        } else {
            $title = "<h1>{$title}</h1>";
        }
        return static::tag('div', $title, $options);
    }
    /**
     * Generates a well container.
     *
     * @param string $content the content
     * @param string $size    the well size - one of the size constants
     *                        - SIZE_TINY   = 'xs';
     *                        - SIZE_SMALL  = 'sm';
     *                        - SIZE_MEDIUM = 'md';
     *                        - SIZE_LARGE  = 'lg';
     * @param array  $options html options for the well container.
     *
     * Example(s):
     * ~~~
     * echo BS::well(
     *    'Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.',
     *    BS::LARGE
     * );
     * ~~~
     * @see http://getbootstrap.com/components/#wells
     * @return string
     */
    public static function well($content, $size = '', $options = [])
    {
        static::addCssClass($options, 'well');
        if (!VarHelper::isEmpty($size)) {
            static::addCssClass($options, 'well-'.$size);
        }
        return static::tag('div', $content, $options);
    }

    /**
     * Generates a media object. Abstract object styles for building various types of
     * components (like blog comments, Tweets, etc) that feature a left-aligned or
     * right-aligned  image alongside textual content.
     *
     * @param string $heading    the media heading
     * @param string $body       the media content
     * @param string $src        URL for the media article source
     * @param string $img        URL for the media image source
     * @param array  $srcOptions html options for the media article link
     * @param array  $imgOptions html options for the media image
     * @param array  $options    html options for the media object container
     * @param string $tag        the media container tag - defaults to 'div'
     *
     * Example(s):
     * ~~~
     * echo BS::media(
     *    'Media heading 1',
     *    'Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.',
     *    '#',
     *    'http://placehold.it/64x64'
     * );
     * ~~~
     * @see http://getbootstrap.com/components/#media
     * @return string
     */
    public static function media(
        $heading = '',
        $body = '',
        $src = '',
        $img = '',
        $srcOptions = [],
        $imgOptions = [],
        $options = [],
        $tag = 'div'
    ) {
        static::addCssClass($options, 'media');
        if (!isset($srcOptions['class'])) {
            static::addCssClass($srcOptions, 'pull-left');
        }
        static::addCssClass($imgOptions, 'media-object');
        $source = static::a(static::img($img, $imgOptions), $src, $srcOptions);
        $heading = (!VarHelper::isEmpty($heading)) ? static::tag('h4', $heading,
            ['class' => 'media-heading']) : '';
        $content = (!VarHelper::isEmpty($body)) ? static::tag('div', $heading."\n".$body,
            ['class' => 'media-body']) : $heading;
        return static::tag($tag, $source."\n".$content, $options);
    }

    /**
     * Generates list of media (useful for comment threads or articles lists).
     *
     * @param array $items   the media items - each element in the array will contain these keys:
     *                       - param string $items the sub media items (optional)
     *                       - param string $heading the media heading
     *                       - param string $body the media content
     *                       - param string $src URL for the media article source
     *                       - param string $img URL for the media image source
     *                       - param array $srcOptions html options for the media article link (optional)
     *                       - param array $imgOptions html options for the media image (optional)
     *                       - param array $itemOptions html options for each media item (optional)
     * @param array $options html options for the media list container
     *
     * Example(s):
     * ~~~
     * echo BS::mediaList([
     *    [
     *      'heading' => 'Media heading 1',
     *      'body' => 'Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin
     *      commodo. '.
     *          'Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.',
     *      'src' => '#',
     *      'img' => 'http://placehold.it/64x64',
     *      'items' => [
     *          [
     *              'heading' => 'Media heading 1.1',
     *              'body' => 'Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante
     *              sollicitudin commodo. ' .
     *                  'Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.',
     *              'src' => '#',
     *              'img' => 'http://placehold.it/64x64'
     *          ],
     *          [
     *              'heading' => 'Media heading 1.2',
     *              'body' => 'Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante
     *              sollicitudin commodo. ' .
     *                  'Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.',
     *              'src' => '#',
     *              'img' => 'http://placehold.it/64x64'
     *          ],
     *      ]
     *    ],
     *  [
     *      'heading' => 'Media heading 2',
     *      'body' => 'Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin
     *      commodo. '.
     *          'Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.',
     *      'src' => '#',
     *      'img' => 'http://placehold.it/64x64'
     *    ],
     * ]);
     * ~~~
     * @see http://getbootstrap.com/components/#media
     * @return string
     */
    public static function mediaList($items = [], $options = [])
    {
        static::addCssClass($options, 'media-list');
        $content = static::generateMediaList($items);
        return static::tag('ul', $content, $options);
    }

    /**
     * Processes media items array to generate a recursive list.
     * @param array   $items the media items
     * @param boolean $top   whether item is the topmost parent
     * @return string
     */
    protected static function generateMediaList($items, $top = true)
    {
        $content = '';
        foreach ($items as $item) {
            $tag = ($top) ? 'li' : 'div';
            if (isset($item['items'])) {
                $item['body'] .= static::generateMediaList($item['items'], false);
            }
            $content .= static::generateMediaItem($item, $tag)."\n";
        }
        return $content;
    }

    /**
     * Processes and generates each media item
     * @param array  $item the media item configuration
     * @param string $tag  the media item container tag
     * @return string
     */
    protected static function generateMediaItem($item, $tag)
    {
        $heading = isset($item['heading']) ? $item['heading'] : '';
        $body = isset($item['body']) ? $item['body'] : '';
        $src = isset($item['src']) ? $item['src'] : '#';
        $img = isset($item['img']) ? $item['img'] : '';
        $srcOptions = isset($item['srcOptions']) ? $item['srcOptions'] : [];
        $imgOptions = isset($item['imgOptions']) ? $item['imgOptions'] : [];
        $itemOptions = isset($item['itemOptions']) ? $item['itemOptions'] : [];
        return static::media($heading, $body, $src, $img, $srcOptions, $imgOptions, $itemOptions, $tag);
    }

    /**
     * Generates a generic close icon button for
     * dismissing content like modals and alerts.
     *
     * @param string $label   the close icon label - defaults to '&times;'
     * @param array  $options html options for the close icon button
     * @param string $tag     the html tag for rendering the close icon - defaults to 'button'
     *
     * Example(s):
     * ~~~
     * echo BS::closeButton();
     * echo BS::closeButton(BS::icon('remove-sign');
     * ~~~
     * @see http://getbootstrap.com/css/#helper-classes-close
     * @return string
     */
    public static function closeButton($label = '&times;', $options = [], $tag = 'button')
    {
        static::addCssClass($options, 'close');
        if ($tag == 'button') {
            $options['type'] = 'button';
        }
        $options['aria-hidden'] = 'true';
        return static::tag($tag, $label, $options);
    }

    /**
     * Generates a caret.
     *
     * @param string  $direction whether to display as 'up' or 'down' direction - defaults to 'down'
     * @param boolean $disabled  if the caret is to be displayed as disabled - defaults to false
     * @param array   $options   html options for the caret container.
     * @param string  $tag       the html tag for rendering the caret - defaults to 'span'
     *
     * Example(s):
     * ~~~
     * echo 'Down Caret ' . BS::caret();
     * echo 'Up Caret ' . BS::caret('up');
     * echo 'Disabled Caret ' . BS::caret('down', true);
     * ~~~
     * @see http://getbootstrap.com/css/#helper-classes-carets
     * @return string
     */
    public static function caret($direction = 'down', $disabled = false, $options = [], $tag = 'span')
    {
        static::addCssClass($options, 'caret');
        if (!isset($options['style'])) {
            $options['style'] = 'margin-bottom: 3px;';
        }
        if ($disabled) {
            $options['style'] = $options['style'].';  border-top-color: #bbb; border-bottom-color: #bbb;';
        }
        if ($direction == 'up') {
            return static::tag($tag, static::tag($tag, '', $options), ['class' => 'dropup']);
        } else {
            return static::tag($tag, '', $options);
        }
    }

    /**
     * Generates an abbreviation.
     *
     * @param string  $content    the abbreviation content
     * @param string  $title      the abbreviation title
     * @param boolean $initialism if set to true, will display a slightly smaller font-size.
     * @param array   $options    html options for the abbreviation
     *
     * Example(s):
     * ~~~
     * echo BS::abbr('HTML', 'HyperText Markup Language')  . ' is the best thing since sliced bread';
     * echo BS::abbr('HTML', 'HyperText Markup Language', true);
     * ~~~
     * @see http://getbootstrap.com/css/#type-abbreviations
     * @return string
     */
    public static function abbr($content, $title, $initialism = false, $options = [])
    {
        $options['title'] = $title;
        if ($initialism) {
            static::addCssClass($options, 'initialism');
        }
        return static::tag('abbr', $content, $options);
    }

    /**
     * Generates a blockquote.
     *
     * @param string $content     the blockquote content
     * @param string $citeContent the content of the citation (optional) - this should typically
     *                            include the wildtag '{source}' to embed the cite source
     * @param string $citeTitle   the cite source title (optional)
     * @param string $citeSource  the cite source (optional)
     * @param array  $options     html options for the blockquote
     *
     * Example(s):
     * ~~~
     * echo BS::blockquote(
     *      'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.',
     *      'Someone famous in {source}',
     *      'International Premier League',
     *      'IPL'
     * );
     * ~~~
     * @param array  $options     html options for the blockquote
     * @see http://getbootstrap.com/css/#type-blockquotes
     * @return string
     */
    public static function blockquote($content, $citeContent = '', $citeTitle = '', $citeSource = '', $options = [])
    {
        $content = static::tag('p', $content);
        if (!VarHelper::isEmpty($citeContent)) {
            $source = static::tag('cite', $citeSource, ['title' => $citeTitle]);
            $content .= "\n<small>".str_replace('{source}', $source, $citeContent)."</small>";
        }
        return static::tag('blockquote', $content, $options);
    }
}