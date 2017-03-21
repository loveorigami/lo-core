<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 16.03.2017
 * Time: 13:05
 */

namespace lo\core\validators;

use Yii;
use yii\base\InvalidConfigException;
use yii\validators\Validator;

class DateRangeLimitDaysValidator extends Validator
{
    /**
     * @var string the name of the attribute to be callable with.
     */
    public $fromAttribute;

    /**
     * @var string
     */
    public $fromValue;

    /**
     * @var integer
     */
    public $days;

    /**
     * @var string the user-defined error message. It may contain the following placeholders which
     * will be replaced accordingly by the validator:
     *
     * - `{attribute}`: the label of the attribute being validated
     * - `{value}`: the value of the attribute being validated
     * - `{callableValue}`: the value or the attribute label to be callable with
     * - `{callableAttribute}`: the label of the attribute to be callable with
     */
    public $message;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('validator', 'Range in "{attribute}" must not exceed {days} days');
        }
        if ($this->fromAttribute === null) {
            throw new InvalidConfigException('DateRangeLimitDaysValidator::fromAttribute must be set');
        }
    }

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (is_array($value)) {
            $this->addError($model, $attribute, Yii::t('yii', '{attribute} is invalid.'));
            return;
        }
        $fromAttribute = $this->fromAttribute;
        $this->fromValue = $model->$fromAttribute;

        if ($value == $this->fromValue) {
            $this->addError($model, $attribute, Yii::t('yii', '{attribute} is invalid.'));
        }

        $result = $this->validateValue($value);
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
            return;
        }
    }

    /**
     * @param mixed $value
     * @return array|null
     */
    protected function validateValue($value)
    {
        if (!$this->validateRange($value)) {
            return [$this->message, [
                'value' => $value,
                'fromAttribute' => $this->fromAttribute,
                'fromValue' => $this->fromValue,
                'days' => $this->days
            ]];
        } else {
            return null;
        }
    }

    /**
     * @param $value
     * @return bool
     */
    protected function validateRange($value)
    {
        $datetime1 = new \Datetime($this->fromValue);
        $datetime2 = new \Datetime($value);
        $interval = $datetime1->diff($datetime2)->days;
        if ($interval > $this->days) return false;
        return true;
    }
}