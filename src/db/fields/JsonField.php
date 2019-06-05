<?php

namespace lo\core\db\fields;

use lo\core\behaviors\JsonBehavior;
use lo\core\db\ActiveRecord;
use lo\core\inputs\ReadOnlyInput;
use lo\core\inputs\TextInput;
use lo\core\inputs\TranslitInput;
use paulzi\jsonBehavior\JsonValidator;
use yii\helpers\ArrayHelper;
use Zelenin\yii\behaviors\Slug;

/**
 * Class JsonField
 *
 * @package lo\core\db\fields
 */
class JsonField extends BaseField
{
    /** Преффикс поведения */
    const BEHAVIOR_PREF = "json";

    /** @var bool */
    public $isRequired = false;

    /** @var string */
    public $filterInputClass = TextInput::class;

    /** @var array настройки поведения генерации uuid */
    public $jsonOptions = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        $parent = parent::behaviors();

        if ($this->model->scenario != ActiveRecord::SCENARIO_SEARCH) {

            $code = self::BEHAVIOR_PREF . ucfirst($this->attr);

            $parent[$code] = ArrayHelper::merge([
                'class' => JsonBehavior::class,
                'attributes' => [$this->attr],
            ], $this->jsonOptions);
        }

        return $parent;
    }

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules[] = array_merge([$this->attr, JsonValidator::class, 'except' => ActiveRecord::SCENARIO_SEARCH]);

        return $rules;
    }
}
