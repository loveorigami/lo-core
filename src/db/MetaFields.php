<?php
namespace lo\core\db;

use Yii;
use yii\base\Object;
use yii\db\Query;
use yii\db\Command;
use lo\core\helpers\ArrayHelper;
use lo\core\db\fields;
use lo\core\inputs;

/**
 * Class MetaFields
 * Класс содержащий описание полей модели
 * @package lo\core\db
 * @property fields\BaseField[] $fields массив обектов полей модели;
 * @property array $fieldsConfig массив конфигураций объектов полей модели;
 */
abstract class MetaFields extends Object
{
    /**
     * Вкладка формы по умолчанию
     */
    const DEFAULT_TAB = "default";

    /**
     * @var ActiveRecord модель - владелец
     */
    protected $owner;

    /**
     * @var array массив объектов полей модели
     */
    protected $_fields;

    /**
     * @var array массив конфигураций объектов полей модели
     */
    protected $_fieldsConfig;

    /**
     * Конструктор
     * @param ActiveRecord $owner
     * @param array $params
     */
    public function __construct(ActiveRecord $owner, $params = array())
    {
        $this->owner = $owner;
        parent::__construct($params);
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        $config = $this->getFieldsConfig();
        if (isset($config[$name]) and is_array($config[$name])) {
            return $this->getField($name);
        }
        return parent::__get($name);
    }

    /**
     * Возвращает массив конфигураций обектов полей модели
     * @return array
     */
    public function getFieldsConfig()
    {
        if (!is_array($this->_fieldsConfig)) {
            $this->_fieldsConfig = ArrayHelper::merge($this->defaultConfig(), $this->config());
        }
        $this->_fieldsConfig = ArrayHelper::multi_order($this->_fieldsConfig);
        return $this->_fieldsConfig;
    }

    /**
     * Возвращает поля по коду вкладки
     * @param string $tab код вкладки
     * @return fields\BaseField[]
     */
    public function getFieldsByTab($tab)
    {
        $fields = $this->getFields();
        $arr = [];
        foreach ($fields AS $field) {
            if ($field->tab == $tab AND $field->showInForm)
                $arr[] = $field;
        }
        return $arr;
    }

    /**
     * Возвращает массив объектов полей модели
     * @param null $names список имен атрибутов, которые необходимо вернуть
     * @param array $except список имен атрибутов, которые необходимо исключить
     * @return fields\BaseField[]
     */
    public function getFields($names = null, $except = [])
    {
        if ($this->_fields === null) {
            $this->_fields = [];
            foreach ($this->fieldsConfig AS $name => $config) {
                if (!is_array($config))
                    continue;
                $this->_fields[$name] = Yii::createObject($config["definition"], $config["params"]);
            }
        }
        $fields = (!empty($names) and is_array($names)) ? array_intersect_key($this->_fields, array_flip($names)) : $this->_fields;
        foreach ($except as $key) {
            unset($fields[$key]);
        }
        return $fields;
    }

    /**
     * Возвращает объект поля модели по его названию
     * @param $name
     * @return fields\BaseField[] | null
     */
    public function getField($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }
        return null;
    }

    /**
     * Конфигурация полей по умолчанию
     * @return array
     */
    protected function defaultConfig()
    {
        return [
            "id" => [
                'definition' => [
                    "class" => fields\PkField::class,
                    "title" => "ID",
                ],
                "params" => [$this->owner, "id"],
                "pos" => 1
            ],
            "author_id" => [
                'definition' => [
                    "class" => fields\HasOneField::class,
                    "title" => Yii::t('core', 'Author'),
                    "showInForm" => false,
                    "showInGrid" => false, //Yii::$app->user->can('editor'),
                    "data" => [$this, "getAuthorsList"],
                    "gridAttr" => "username",
                    "eagerLoading" => true,
                ],
                "params" => [$this->owner, "author_id", "author"],
                "pos" => 20
            ],
            "created_at" => [
                'definition' => [
                    "class" => fields\TimestampField::class,
                    "title" => Yii::t('core', 'Created'),
                    "showInGrid" => false,
                    "showInFilter" => false,
                    "filterInputClass" => [
                        "class" => inputs\DateRangeInput::class,
                        "fromAttr" => "createdAtFrom",
                        "toAttr" => "createdAtTo",
                    ],
                    "inputClassOptions" => [
                        "widgetOptions" => [
                            'clientOptions' => [
                                //'startDate' =>  new \yii\web\JsExpression('new Date()'),
                                'format' => 'yyyy-mm-dd'
                            ]
                        ],
                    ],
                    "queryModifier" => [$this, "createdAtQueryModifier"],
                ],
                "params" => [$this->owner, "created_at"],
                "pos" => 25
            ],
            "updated_at" => [
                'definition' => [
                    "class" => fields\TimestampField::class,
                    "title" => Yii::t('core', 'Updated'),
                    "showInExtendedFilter" => false,
                ],
                "params" => [$this->owner, "updated_at"]
            ],
            "status" => [
                "definition" => [
                    "class" => fields\CheckBoxField::class,
                    'inputClass' => inputs\CheckBoxInputB::class, // bootstrap toggle
                    "title" => Yii::t('core', 'Status'),
                    "editInGrid" => true,
                    "initValue" => true,
                    "inputClassOptions" => [
                        "widgetOptions" => [
                            'options' => [
                                'label' => null,
                                'inline' => true,
                                'data-on' => Yii::t('common', 'Yes'),
                                'data-off' => Yii::t('common', 'No'),
                            ],
                        ],
                    ],
                ],
                "params" => [$this->owner, "status"],
                "pos" => 50
            ],
        ];
    }

    /**
     * Поиск по диапазону дат создания
     * @param ActiveQuery $q
     * @param fields\BaseField $f
     * @var
     */
    public function createdAtQueryModifier($q, $f)
    {
        $table = $f->model->tableName();
        $attr = $f->attr;

        if ($f->model->createdAtFrom) {
            $q->andFilterWhere([">=", "$table.$attr", strtotime($f->model->createdAtFrom)]);
        }

        if ($f->model->createdAtTo) {
            $q->andFilterWhere(["<=", "$table.$attr", strtotime($f->model->createdAtTo)]);
        }

    }

    /**
     * Возвращает список авторов
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getAuthorsList()
    {
        $authorQuery = Yii::createObject(Query::class);
        /** @var Command $authorCommand */
        $authorCommand = $authorQuery->select('id, username')->from(Yii::$app->getDb()->tablePrefix . 'user')->createCommand();
        $authors = $authorCommand->queryAll();
        return ArrayHelper::map($authors, 'id', 'username');
    }

    /**
     * Данный метод должен возвращать массив конфигураций объектов для создания полей модели
     * через Yii::createObject()
     *
     * Пример конфигурации:
     *
     * return [
     *
     *      "title"=>[
     *                  "definition"=>[
     *                      "class"=>\lo\core\db\fields\TextField::className(),
     *                      "title"=>"Название",
     *                  ],
     *                  "params"=>[$this->owner, "title"]
     *              ],
     * ];
     *
     * @return array
     */
    abstract protected function config();

    /**
     * Массив вкладок формы редактирования модели (key=>name)
     * @return array
     */
    public function tabs()
    {
        return [self::DEFAULT_TAB => Yii::t('core', 'Element')];
    }
}