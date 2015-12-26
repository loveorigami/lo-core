<?php

namespace lo\core\grid;

use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\select2\Select2;

/**
 * Class Select2Column
 * Фильтр
 * @package lo\core\grid
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Select2Column extends DataColumn
{
    public $loadUrl=[];
    /**
     * @inheritdoc
     */

    protected function renderFilterCellContent2()
    {
        $url = \yii\helpers\Url::to($this->loadUrl);

        $model = $this->grid->filterModel;

        $widgetOptions = [
// With a model and without ActiveForm

            'model' => $model,
            'name' => 'lib_id',
            'data' => $this->filter,
            'options' => ['placeholder' => 'Select a state ...'],
        ];

        return Select2::widget($widgetOptions);
       // return Html::activeDropDownList($model, $this->attribute, $this->filter);
    }


    /**
     * @inheritdoc
     */
    protected function renderFilterCellContent()
    {

        $model = $this->grid->filterModel;

            if ($model->hasErrors($this->attribute)) {
                Html::addCssClass($this->filterOptions, 'has-error');
                $error = ' ' . Html::error($model, $this->attribute, $this->grid->filterErrorOptions);
            } else {
                $error = '';
            }
            if (is_array($this->filter)) {
                $options = array_merge(['prompt' => ''], $this->filterInputOptions);
                return Html::activeDropDownList($model, $this->attribute, $this->filter, $options) . $error;

/*                return Select2::widget([
                    'name' => $this->attribute,
                    //'attribute' => $this->attribute,
                    'data' => $this->filter,
                    'options' => $options,
                ]) . $error;*/

            } else {
                return Html::activeTextInput($model, $this->attribute, $this->filterInputOptions) . $error;
            }

    }


} 