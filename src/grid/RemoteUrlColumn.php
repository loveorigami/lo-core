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
 * Class RemoteUrlColumn
 *
 * @package lo\core\grid
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class RemoteUrlColumn extends DataColumn
{
    public $contentOptions = ['class' => 'text-center'];
    public $headerOptions = ['max-width' => '40'];
    public $format = 'raw';
    public $label = '';
    public $filter = false;

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

        return Html::a(FA::i(FA::_LINK), $value, [
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
