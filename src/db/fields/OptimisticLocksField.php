<?php

namespace lo\core\db\fields;

use lo\core\behaviors\upload\UploadImage;
use lo\core\inputs;
use yii\behaviors\OptimisticLockBehavior;
use yii\helpers\ArrayHelper;

/**
 * Class OptimisticLocksField
 *
 * @package lo\core\db\fields
 */
class OptimisticLocksField extends BaseField
{
    /** @var string */
    public $inputClass = inputs\HiddenInput::class;

    /** Преффикс поведения */
    const BEHAVIOR_PREF = 'optlock';

    public $showInGrid = false;
    public $showInExtendedFilter = false;
    public $showInFilter = false;
    public $showInForm = true;

    /**
     * @return array
     */
    public function behaviors()
    {
        $parent = [];
        $code = self::BEHAVIOR_PREF.$this->attr;
        $parent[$code] = [
            'class' => OptimisticLockBehavior::class,
        ];

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
        $rules[] = [$this->attr, 'integer'];

        return $rules;
    }
}
