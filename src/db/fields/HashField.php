<?php
namespace lo\core\db\fields;

use lo\core\behaviors\HashEmail;
use lo\core\db\ActiveRecord;
use lo\core\behaviors\HashText;
use lo\core\inputs\ReadOnlyInput;

/**
 * Class HashField
 * Поле хеша
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class HashField extends TextField
{
    /**
     * @inheritdoc
     */
    public $inputClass = ReadOnlyInput::class;

    /**
     * Преффикс поведения
     */
    const BEHAVIOR_PREF = "hash";

    /**
     * hash modes
     */
    const MODE_TEXT = "text";
    const MODE_EMAIL = "email";

    /**
     * @var string атрибут из которого генерировать хеш
     */
    public $generateFrom = 'text';

    /**
     * @var string
     */
    public $hashMode = self::MODE_TEXT;

    /**
     * @var string
     */
    private $_class;

    /**
     * get hash class behavior
     */
    public function init()
    {
        parent::init();
        switch ($this->hashMode){
            case self::MODE_EMAIL:
                $this->_class = HashEmail::class;
                break;
            default:
                $this->_class = HashText::class;

        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $parent = parent::behaviors();

        if (!empty($this->generateFrom) AND $this->model->scenario != ActiveRecord::SCENARIO_SEARCH) {

            $code = self::BEHAVIOR_PREF . ucfirst($this->attr);

            $parent[$code] = [
                'class' => $this->_class,
                'hashAttribute' => $this->attr,
                'attribute' => $this->generateFrom,
            ];
        }

        return $parent;
    }
}