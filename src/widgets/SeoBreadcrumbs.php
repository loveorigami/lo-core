<?php

namespace lo\core\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/**
 * Widget provides SEO schema for Breadcrumbs widget
 * @package lo\core\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class SeoBreadcrumbs extends Breadcrumbs
{
    /**
     * @var array the HTML seo attributes for the breadcrumb container tag
     */
    public $seoOptions = [
        'itemscope' => true,
        'itemtype' => 'https://schema.org/BreadcrumbList'
    ];

    /**
     * @inheritdoc
     */
    public $itemTemplate = "<li itemprop=\"itemListElement\" itemscope itemtype=\"https://schema.org/ListItem\">{link}<meta itemprop=\"position\" content=\"{position}\" /></li>";

    /**
     * @inheritdoc
     */
    public $activeItemTemplate = "<li class=\"active\" itemprop=\"itemListElement\" itemscope itemtype=\"https://schema.org/ListItem\">{link}<meta itemprop=\"position\" content=\"{position}\" /></li>";

    /**
     * @var integer
     */
    protected $_position = 1;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->options = array_merge($this->options, $this->seoOptions);
    }

    /**
     * @param array $link the link to be rendered. It must contain the "label" element. The "url" element is optional.
     * @param string $template
     * @return string
     * @throws InvalidConfigException
     */
    protected function renderItem($link, $template)
    {
        $visible = ArrayHelper::getValue($link, 'visible', true);
        if (!$visible) return null;

        $encodeLabel = ArrayHelper::remove($link, 'encode', $this->encodeLabels);
        if (array_key_exists('label', $link)) {
            $label = $encodeLabel ? Html::encode($link['label']) : $link['label'];
        } else {
            throw new InvalidConfigException('The "label" element is required for each link.');
        }
        if (isset($link['template'])) {
            $template = $link['template'];
        }
        $labelSeoOptions = ['itemprop' => 'name'];
        $label = Html::tag('span', $label, $labelSeoOptions);
        if (isset($link['url'])) {
            $options = $link;
            unset($options['template'], $options['label'], $options['url']);
            $linkSeoOptions = [
                'itemscope' => true,
                'itemtype' => 'https://schema.org/Thing',
                'itemprop' => 'item'
            ];
            $options = array_merge($options, $linkSeoOptions);
            $link = Html::a($label, $link['url'], $options);
        } else {
            $link = $label;
        }
        return strtr($template, [
            '{link}' => $link,
            '{position}' => $this->_position++
        ]);
    }
}