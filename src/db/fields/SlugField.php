<?php
namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;
use lo\core\inputs\TextInput;
use lo\core\inputs\TranslitInput;
use yii\helpers\ArrayHelper;
use Zelenin\yii\behaviors\Slug;

/**
 * Class SlugField
 * Поле символьного кода
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class SlugField extends TextField
{
    /** Преффикс поведения */
    const BEHAVIOR_PREF = "slug";

    /** @var bool */
    public $isRequired = true;

    /** @var array параметры валидатора уникальности */
    public $uniqueParams = [];

    /** @var string атрибут из которого генерировать символьный код */
    public $generateFrom = 'name';

    /** @var array настройки поведения генерации символьного кода */
    public $slugOptions = [];

    /** @var string */
    public $inputClass = TranslitInput::class;

    /** @var string */
    public $filterInputClass = TextInput::class;

    /**
     * @return array
     */
    public function behaviors()
    {
        $parent = parent::behaviors();

        if(!empty($this->generateFrom) AND $this->model->scenario != ActiveRecord::SCENARIO_SEARCH) {

            $code = self::BEHAVIOR_PREF . ucfirst($this->attr);

            $parent[$code] = ArrayHelper::merge([
                'class' => Slug::class,
                'slugAttribute' => $this->attr,
                'attribute' => $this->generateFrom,
                'ensureUnique' => true,
                'replacement' => '-',
                'lowercase' => true,
                'immutable' => true,
                'uniqueValidator' => $this->uniqueParams,
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFKC; [ʹ, ʺ] Remove; [:Punctuation:] Remove;'
            ], $this->slugOptions);
        }

        return $parent;
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        if(empty($this->generateFrom)){
            $rules[] = array_merge([$this->attr, 'unique', 'except' => ActiveRecord::SCENARIO_SEARCH], $this->uniqueParams);
        }

        $rules[] = [$this->attr, 'match', 'pattern' => '/^[A-z0-9_-]+$/i'];

        return $rules;
    }

}