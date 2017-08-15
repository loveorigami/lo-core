<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 16.03.2017
 * Time: 13:05
 */

namespace lo\core\validators;

use Yii;
use yii\validators\Validator;

class NameLastnameValidator extends Validator
{
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
            $this->message = Yii::t('validator', 'The field must have name and lastname');
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
        if (strpos($value, 0x20) == false) {
            return [$this->message, [
                'value' => $value,
            ]];
        } else {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $message = json_encode($this->message);
        $skipOnEmpty = (($this->skipOnEmpty) ? 'if(name == \'\') return true;' : '');
        return <<<JS
            if(typeof(validateNameLastname) != 'function'){
                function validateNameLastname(name){
                    {$skipOnEmpty}
                    var i = name.indexOf(" ");
                    if(i < 0){
                        return false;
                    } 
                    return true;
                }
            }
            if(!validateNameLastname(value)){
                messages.push($message);
            }
JS;
    }
}