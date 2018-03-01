<?php

namespace lo\core\grid;

use lo\core\db\ActiveRecord;
use yii\base\InvalidArgumentException;
use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * Class MultifieldColumn
 * Usage example:
 * [
 *     'class' => \yiister\grid\widgets\MultifieldColumn::className(),
 *     'attribute' => 'name',
 *     'label' => 'Name + slug',
 *     'attributes' => ['slug'],
 *     'template' => '{name}<br /><small><code>{slug}</code></small>',
 * ],
 */
class MultifieldColumn extends DataColumn
{
    /**
     * @var string[] the secondary attribute names array
     */
    public $attributes = [];

    /**
     * @var string|callable the string output template or the render callable
     * The string allows all attribute names from `$attributes` array and from `$attribute` string placed between `{` and `}`
     * Example:
     * 'template' => '{name}<br /><small><code>{slug}</code></small>',
     *
     * The callable gets two parameters (`$model` and `$column`) and has to return output string.
     * Example:
     * 'template' => function ($model, $column) {
     *     $attributeValues = [];
     *     foreach ($column->attributes as $attribute) {
     *         $attributeValues[] = $model->{$attribute};
     *     }
     *     return $model->{$column->attribute} . Html::tag('small', implode('<br />', $attributeValues));
     * },
     */
    public $template;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (is_callable($this->template) && is_string($this->template)) {
            throw new InvalidArgumentException('Unknown template. You can use a string or a callback.');
        }
        if (empty($this->template)) {
            $this->template = function (ActiveRecord $model, MultifieldColumn $column) {
                $attributeValues = [];
                foreach ($column->attributes as $attribute) {
                    $attributeValues[] = $model->{$attribute};
                }
                return $model->{$column->attribute} . '<p>' . Html::tag('span', implode('<br>', $attributeValues), ['class' => 'label label-default']) . '</p>';
            };
        }
        /** fix for url encoded value */
        $this->template = str_replace(['%7B', '%7D'], ['{', '}'], $this->template);
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if (is_callable($this->template)) {
            return call_user_func($this->template, $model, $this);
        }
        $pairs = ['{' . $this->attribute . '}' => $model->{$this->attribute}];
        foreach ($this->attributes as $attribute) {
            $pairs['{' . $attribute . '}'] = $model->$attribute;
        }
        return strtr($this->template, $pairs);
    }
}