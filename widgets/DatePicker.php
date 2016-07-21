<?php
namespace lo\core\widgets;

use Yii;
use yii\helpers\Html;
use yii\base\InvalidParamException;
use kartik\datetime\DateTimePicker;

/**
 * Class DatePicker
 * Реализует выбор дней по календарю
 * @package lo\core\widgets
 */
class DatePicker extends DateTimePicker
{
    public $saveDateFormat = 'php:Y-m-d';

    private $attributeValue = null;

    /**
     * DatePicker constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $defaultOptions = [
            'type' => static::TYPE_COMPONENT_APPEND,
            'convertFormat' => true,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => Yii::$app->formatter->dateFormat,
            ],
        ];
        $config = array_replace_recursive($defaultOptions, $config);

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->hasModel()) {
            $model = $this->model;
            $attribute = $this->attribute;
            $value = $model->$attribute;

            $this->model = null;
            $this->attribute = null;
            $this->name = Html::getInputName($model, $attribute);
            $this->attributeValue = $value;

            if ($value) {
                try {
                    $this->value = Yii::$app->formatter->asDatetime($value, $this->pluginOptions['format']);
                } catch (InvalidParamException $e) {
                    $this->value = null;
                }
            }
        }

        return parent::init();
    }


}