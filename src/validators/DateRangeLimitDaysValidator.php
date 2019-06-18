<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 16.03.2017
 * Time: 13:05
 */

namespace lo\core\validators;

use Carbon\Carbon;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\validators\Validator;

/**
 * Class DateRangeLimitDaysValidator
 *
 * @package lo\core\validators
 */
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
     * @var string
     */
    public $fromLabel;

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

    public $skipOnEmpty = true;

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
     * @param string          $attribute
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
        $this->fromLabel = $model->getAttributeLabel($fromAttribute);

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
            return [
                $this->message,
                [
                    'value' => $value,
                    'fromAttribute' => $this->fromAttribute,
                    'fromLabel' => $this->fromLabel,
                    'fromValue' => $this->fromValue,
                    'days' => $this->days,
                ],
            ];
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
        /** Проверка даты */
        try {
            $datetime1 = Carbon::parse($this->fromValue);
            $datetime2 = Carbon::parse($value);
        } catch (Throwable $exception) {
            return false;
        }

        if ($datetime1 >= $datetime2) {
            $this->message = Yii::t('validator', '"{attribute}" must be more when "{fromLabel}"');

            return false;
        }

        $interval = $datetime1->diff($datetime2)->days;
        if ($interval > $this->days) {
            $this->message = Yii::t('validator', 'Range in "{attribute}" must not exceed {days} days');

            return false;
        }

        return true;
    }
}
