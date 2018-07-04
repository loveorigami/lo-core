<?php
namespace lo\core\db;

use lo\core\behaviors\BlameableBehavior;
use lo\core\traits\ConstraintTrait;
use lo\core\traits\CreatedAtSearchTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord as YiiRecord;
use Yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

/**
 * Class ActiveRecord
 * Надстройка над ActiveRecord фпеймворка.
 * @property $id;
 * @property $name;
 * @property $status;
 * @property $created_at;
 * @property $updated_at;
 *
 * @method ActiveQuery hasMany($class, array $link) see [[BaseActiveRecord::hasMany()]] for more info
 * @method ActiveQuery hasOne($class, array $link) see [[BaseActiveRecord::hasOne()]] for more info
 */
abstract class ActiveRecord extends YiiRecord
{
    use CreatedAtSearchTrait; // для поиска по диапазону
    use ConstraintTrait;

    /** Сценарии валидации*/
    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = "update";
    const SCENARIO_SEARCH = "search";

    /** Значение сортировки по умолчанию */
    const DEFAULT_SORT = 500;

    /** @var array значение сортировки по умолчанию */
    protected $_defaultSearchOrder = ["id" => SORT_DESC];

    /** @var array Базовые сценарии */
    protected $_baseScenarios = [
        self::SCENARIO_INSERT,
        self::SCENARIO_UPDATE,
        self::SCENARIO_SEARCH
    ];

    /** @var MetaFields объект с описанием полей модели */
    protected $_metaFields;

    /** @var array массив сценариев при которых инициалихируются начальные значения */
    protected $initScenarios = [self::SCENARIO_INSERT];

    /** @var boolean использовать настройки модели по умолчанию */
    public $useDefaultConfig = true;

    /** @var string */
    public $userClass = 'dektrium\user\models\User';

    /**
     * Возвращает имя сущности
     * @return string
     */
    public static function getEntity()
    {
        return hash('crc32', get_called_class());
    }

    /**
     * Возвращает имя сущности
     * @return string
     */
    public static function getEntityName()
    {
        return get_called_class();
    }

    /**
     * @return ActiveQuery
     */
    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }

    /**
     * Find record(s) by pk. Allow use variants:
     * - findByPk(1)
     * - findByPk([1,2])
     * - findByPk(['id1' => 1, 'id2' => 2])
     * - findByPk([
     *      ['id1' => 1, 'id2' => 2],
     *      ['id1' => 3, 'id2' => 4]
     *   ])
     * @inheritdoc
     * @return ActiveQueryInterface the newly created [[ActiveQueryInterface|ActiveQuery]] instance.
     */
    public static function findByPk($pk)
    {
        $query = static::find();
        if (ArrayHelper::isAssociative($pk)) {
            $keys = array_keys($pk);
            if (!static::isPrimaryKey($keys)) {
                throw new InvalidArgumentException(get_called_class() . ' has no composite primary key named "' . implode(', ', $keys) . '".');
            }
            // hash condition
            return $query->andWhere($pk);
        } elseif (ArrayHelper::isIndexed($pk, true)) {
            if (is_array($pk[0])) {
                $condition = ['or'];
                foreach ($pk as $compositePk) {
                    $keys = array_keys($compositePk);
                    if (!static::isPrimaryKey($keys)) {
                        throw new InvalidArgumentException(get_called_class() . ' has no composite primary key named "' . implode(', ', $keys) . '".');
                    }
                    $condition[] = ['and', $compositePk];
                }
                return $query->andWhere($condition);
            }
        }
        // query by primary key
        $primaryKey = static::primaryKey();
        if (isset($primaryKey[0])) {
            return $query->andWhere([$primaryKey[0] => $pk]);
        } else {
            throw new InvalidConfigException(get_called_class() . ' must have a primary key.');
        }
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne($this->userClass, ['id' => 'author_id']);
    }
    
    /**
     * @inheritdoc
     * Устанавливаем активность по умолчанию при создании новой модели
     */
    public function init()
    {
        if (in_array($this->scenario, $this->initScenarios)) {
            $this->initValues();
        }
    }

    /**
     * Инициализация начальных значений
     */
    public function initValues()
    {
        $fields = $this->getMetaFields()->getFields();

        foreach ($fields AS $field) {
            $attr = $field->attr;
            if ($field->initValue instanceof \Closure) {
                $this->$attr = call_user_func($field->initValue);
            } elseif ($field->initValue != null) {
                $this->$attr = $field->initValue;
            }
        }
    }

    /**
     * Сченари валидации
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        foreach ($this->_baseScenarios AS $scenario) {
            if (!isset($scenarios[$scenario])) {
                $scenarios[$scenario] = $scenarios[YiiRecord::SCENARIO_DEFAULT];
            }
        }

        return $scenarios;
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function rules()
    {
        $fields = $this->getMetaFields()->getFields();

        $rules = [
            [["createdAtFrom", "createdAtTo"], "safe"],
        ];

        foreach ($fields AS $field) {
            if ($field->rules()) {
                $rules = array_merge($rules, $field->rules());
            }
        }

        return $rules;
    }

    /**
     * @return MetaFields|object
     * @throws InvalidConfigException
     */
    public function getMetaFields()
    {
        if ($this->_metaFields === null) {
            $class = $this->metaClass();
            $this->_metaFields = Yii::createObject($class, [$this]);
        }

        return $this->_metaFields;
    }

    /**
     * Возвращает имя класса содержащего описание полей модели
     * @return string
     */
    public abstract function metaClass();

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function attributeLabels()
    {
        $fields = $this->getMetaFields()->getFields();
        $labels = [
            "createdAtFrom" => Yii::t('core', 'Created from'),
            "createdAtTo" => Yii::t('core', 'Created to'),
        ];

        foreach ($fields AS $field) {
            $labels = ArrayHelper::merge($labels, $field->getAttributeLabel());
        }

        return $labels;
    }

    /**
     * Поведения по умолчанию
     * @return array
     */
    protected function getDefaultBehaviors()
    {
        if (!$this->useDefaultConfig) {
            return [];
        }

        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => 'updater_id',
                'defaultUserId' => 1,
            ],
        ];
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function behaviors()
    {
        $fields = $this->getMetaFields()->getFields();

        $behaviors = $this->getDefaultBehaviors();

        foreach ($fields AS $field) {
            if ($field->behaviors()) {
                $behaviors = array_merge($behaviors, $field->behaviors());
            }
        }

        return $behaviors;
    }

    /**
     * @param $params
     * @param array $dataProviderConfig
     * @param null $query
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function search($params, $dataProviderConfig = [], $query = null)
    {
        $fields = $this->getMetaFields()->getFields();

        $query = $query ? $query : static::find();

        $config = array_merge([
            'class' => ActiveDataProvider::class,
            "query" => $query,
        ], $dataProviderConfig);

        /**@var ActiveDataProvider $dataProvider */
        $dataProvider = Yii::createObject($config);
        $dataProvider->getSort()->defaultOrder = $this->_defaultSearchOrder;

        $this->load($params);
        $this->validate();

        foreach ($fields AS $field) {
            if ($field->search) {
                $field->applySearch($query);
            }
        }

        return $dataProvider;
    }

    /**
     * Изменилась ли активность модели
     * @return bool
     */
    public function hasChangeActive()
    {
        $oldArr = $this->oldAttributes;
        $new = (int)$this->status;
        $old = (int)$oldArr["status"];
        if ($new != $old)
            return true;

        return false;
    }

    /**
     * Возвращает название элемента сущности
     * @return string
     */
    public function getItemLabel()
    {
        $res = [];

        if ($this->hasAttribute("id") AND $this->id)
            $res[] = $this->id;

        if ($this->hasAttribute("name") AND $this->name)
            $res[] = $this->name;

        return implode(" - ", $res);
    }

}
