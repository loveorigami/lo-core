<?php
namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;
use yii\helpers\ArrayHelper;


/**
 * Class HashField
 * Поле символьного кода
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class HashField extends TextField
{

    /**
     * Преффикс поведения
     */
    const BEHAVIOR_PREF = "hash";

    /**
     * @var string атрибут из которого генерировать хеш
     */

    public $generateFrom = 'text';


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $parent = parent::behaviors();

        if(!empty($this->generateFrom) AND $this->model->scenario != ActiveRecord::SCENARIO_SEARCH) {

            $code = self::BEHAVIOR_PREF . ucfirst($this->attr);

            $parent[$code] = [
                'class' => \lo\core\behaviors\HashText::class,
                'hashAttribute' => $this->attr,
                'attribute' => $this->generateFrom,
            ];

        }

        return $parent;
    }

}