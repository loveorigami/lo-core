<?php

namespace lo\core\db\fields;

use lo\core\behaviors\UuidBehavior;
use lo\core\db\ActiveRecord;
use lo\core\inputs\ReadOnlyInput;
use lo\core\inputs\TextInput;
use lo\core\inputs\TranslitInput;
use yii\helpers\ArrayHelper;
use Zelenin\yii\behaviors\Slug;

/**
 * Class UuidField
 *
 * @package lo\core\db\fields
 */
class UuidField extends TextField
{
    /** Преффикс поведения */
    const BEHAVIOR_PREF = "uuid";

    /** @var bool */
    public $isRequired = false;

    /** @var string */
    public $inputClass = ReadOnlyInput::class;

    /** @var string */
    public $filterInputClass = TextInput::class;

    /** @var array настройки поведения генерации uuid */
    public $uuidOptions = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        $parent = parent::behaviors();

        if ($this->model->scenario != ActiveRecord::SCENARIO_SEARCH) {

            $code = self::BEHAVIOR_PREF . ucfirst($this->attr);

            $parent[$code] = ArrayHelper::merge([
                'class' => UuidBehavior::class,
                'uuidAttribute' => $this->attr,
            ], $this->uuidOptions);
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

        $rules[] = array_merge([$this->attr, 'unique', 'except' => ActiveRecord::SCENARIO_SEARCH]);

        $rules[] = [$this->attr, 'match', 'pattern' => '/^[A-z0-9_-]+$/i'];

        return $rules;
    }
}
