<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 27.06.2017
 * Time: 17:29
 */

namespace lo\core\grid;

use Closure;
use lo\core\helpers\FA;
use lo\core\url\FrontendUrlHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * Class FrontendUrlColumn
 *
 * @package lo\core\grid
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class FrontendUrlColumn extends DataColumn
{
    public $base_url;
    public $route;
    public $contentOptions = ['class' => 'text-center'];
    public $headerOptions = ['max-width' => '40'];
    public $format = 'raw';
    public $label = '';
    public $filter = false;

    /**
     * For url param in route
     * ['pattern' => 'page/<pageSlug>', 'route' => 'page/view'],
     *
     * @var string
     */
    public $params;

    /**
     * @param mixed $model
     * @param mixed $key
     * @param int   $index
     * @return string
     * @throws InvalidConfigException
     */
    protected function renderDataCellContent($model, $key, $index): string
    {
        $value = $this->getDataCellValue($model, $key, $index);
        $attr = $this->params ?? $this->attribute;

        if ($this->route) {
            $url = [$this->route, $attr => $value];
        } else {
            $url = $value;
        }

        $baseUrl = $this->base_url;

        if ($this->base_url instanceof Closure) {
            $baseUrl = call_user_func($this->base_url, $model);
        }

        return Html::a(FA::i(FA::_LINK),
            FrontendUrlHelper::url($url, $baseUrl), [
                'title' => Yii::t('core', 'on site'),
                'target' => '_blank',
                'class' => 'btn btn-primary btn-xs',
                'data' => [
                    'pjax' => 0,
                ],
            ]
        );
    }
}
