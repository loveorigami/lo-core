<?php

namespace lo\core\db\fields;

use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;
use lo\core\db\MetaFields;
use lo\core\grid\UpdateColumn;
use lo\core\grid\XEditableColumn;
use lo\core\inputs;
use lo\core\interfaces\IField;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii\widgets\ActiveForm;

/**
 * Class BaseField
 * Базовый класс полей.
 *
 * @package lo\core\db\fields
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class BaseField extends BaseObject implements IField
{
    /** @var ActiveRecord модель */
    public $model;

    /** @var string атрибут модели */
    public $attr;

    /** @var string подпись атрибута */
    public $title;

    /** @var string || array url для подзагрузки данных через ajax */
    public $loadUrl;

    /** @var bool жадная загрузка */
    public $eagerLoading = true;

    /** @var mixed значение присваевоемое полю при создании модели с сценарием ActiveRecord::SCENARIO_INSERT */
    public $initValue;

    /** @var mixed значение поля присваевоемое модели перед сохранением, в случае если текущий атрибут не задан */
    public $defaultValue;

    /** @var string вкладка формы на которой должно быть расположено поле */
    public $tab = MetaFields::DEFAULT_TAB;

    /** @var bool отображать в гриде */
    public $showInGrid = true;

    /** @var bool отображать при детальном просмотре */
    public $showInView = true;

    /** @var bool отображать в форме */
    public $showInForm = true;

    /** @var bool отображать в фильтре грида */
    public $showInFilter = true;

    /** @var bool отображать в расширенном фильре */
    public $showInExtendedFilter = true;

    /** @var bool отображать поле при табличном вводе */
    public $showInTableInput = true;

    /** @var bool использоваь ли валидатор safe */
    public $isSafe = true;

    /** @var bool обязательно ли поле к заполнению */
    public $isRequired = false;

    /** @var bool участвует ли поле при поиске */
    public $search = true;

    /** @var bool возможность редактирования значения поля в гриде */
    public $editInGrid = false;

    /** @var string действие для редактирования модели из грида */
    public $editableAction = 'editable';

    /** @var bool возможность перейти к редактированию */
    public $updateInGrid = false;

    /** @var string действие для перехода к редактированию */
    public $updateAction = 'update';

    /** @var array опции по умолчанию при отображении в гриде */
    public $gridOptions = [];

    /** @var array опции по умолчанию при детальном просмотре */
    public $viewOptions = [];

    /** @var callable функция возвращающая данные ассоциированные с полем */
    public $data;

    /** @var string|array имя класс, либо конфигурация компонента который рендерит поле вывода формы */
    public $inputClass = inputs\TextInput::class;

    /** @var array параметры поля ввода */
    public $inputClassOptions = [];

    /** @var string|array имя класса, либо конфигурация компонента который рендерит поле ввода расширенного фильтра */
    public $filterInputClass;

    /** @var string шаблон для поля */
    public $formTemplate = '<div class="row"><div class="col-xs-12 col-md-12 col-lg-12">{input}</div></div>';

    /**
     * @var callable функция для применения ограничений при поиске по полю.
     * Принимает два аргумента \yii\db\ActiveQuery и \lo\core\db\fields\Field
     */
    public $queryModifier;

    /**
     * @var array массив дополнительный правил валидации для поля
     * ["validatorName" => ["prop" => "value"] ... ]
     */
    public $rules = [];

    /** @var array данные ассоциированные с полем (key=>value) */
    protected $_dataValue;

    /** @var mixed значение фильтра грида установленное */
    protected $_gridFilter;

    /**
     * Конструктор
     *
     * @param ActiveRecord $model  модель
     * @param string       $attr   атрибут
     * @param array        $config массив значений атрибутов
     */
    public function __construct(ActiveRecord $model, $attr, $config = [])
    {
        parent::__construct($config);

        $this->model = $model;
        $this->attr = $attr;
    }

    /**
     * Формирование Html кода поля для вывода в расширенном фильтре
     *
     * @param ActiveForm $form    объект форма
     * @param array      $options массив html атрибутов поля
     * @return string
     * @throws InvalidConfigException
     */
    public function getExtendedFilterForm(ActiveForm $form, Array $options = [])
    {
        return $this->getForm($form, $options, false, $this->filterInputClass);
    }

    /**
     * Формирование Html кода поля для вывода в форме
     *
     * @param ActiveForm   $form    объект форма
     * @param array        $options массив html атрибутов поля
     * @param bool|int     $index   индекс модели при табличном вводе
     * @param string|array $cls     класс поля, либо конфигурационный массив
     * @throws InvalidConfigException
     * @return string
     */
    public function getForm(ActiveForm $form, Array $options = [], $index = false, $cls = null)
    {
        $cls = $cls ?: $this->inputClass;

        $inputClass = \is_array($cls) ? $cls : ['class' => $cls];

        $input = Yii::createObject(ArrayHelper::merge([
            'modelField' => $this,
        ], $inputClass, $this->inputClassOptions));

        return $input->renderInput($form, $options, $index);
    }

    /**
     * Формирует html код поля формы обернутый в шаблон
     *
     * @param ActiveForm $form    объект форма
     * @param array      $options массив html атрибутов поля
     * @param bool|int   $index   индекс модели при табличном вводе
     * @return string
     * @throws InvalidConfigException
     */
    public function getWrappedForm(ActiveForm $form, Array $options = [], $index = false)
    {
        $html = $this->getForm($form, $options, $index);

        return str_replace('{input}', $html, $this->formTemplate);
    }

    /**
     * Возвращает имя атрибута для поля формы
     *
     * @param bool|int $index индекс модели при табличном вводе
     * @return string
     */
    protected function getFormAttrName($index)
    {
        return ($index !== false) ? "[$index]{$this->attr}" : $this->attr;
    }

    /**
     * Конфигурация грида по умолчанию
     *
     * @return array
     */
    protected function defaultGrid()
    {
        $grid = ['attribute' => $this->attr, 'label' => $this->title];

        if ($this->showInFilter) {
            $grid['filter'] = $this->getGridFilter();
        } else {
            $grid['filter'] = false;
        }

        if ($this->editInGrid) {
            $grid = array_merge($grid, $this->xEditable());
        }

        if ($this->updateInGrid && !$this->editInGrid) {
            $grid = array_merge($grid, $this->linkUpdate());
        }

        return $grid;
    }

    /**
     * Конфигурация поля для грида (GridView)
     *
     * @return array
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();

        $grid["value"] = function ($model) {
            return $this->getGridValue($model);
        };

        return $grid;
    }

    /**
     * Результурующая конфигурация поля грида (GridView)
     *
     * @return array
     */
    final public function getGrid()
    {
        return ArrayHelper::merge($this->grid(), $this->gridOptions);
    }

    /**
     * Вывод значения в гриде
     *
     * @param $model
     * @return string
     */
    protected function getGridValue($model)
    {
        return $model->{$this->attr};
    }

    /**
     * Возвращает значение фильтра для грида
     *
     * @return mixed
     */
    public function getGridFilter()
    {
        if ($this->_gridFilter !== null) {
            return $this->_gridFilter;
        }

        return $this->defaultGridFilter();
    }

    /**
     * @param $value mixed установка значения фильтра
     */
    public function setGridFilter($value)
    {
        $this->_gridFilter = $value;
    }

    /**
     * Возвращает значение фильтра для поля по умолчанию
     *
     * @return mixed
     */
    protected function defaultGridFilter()
    {
        return true;
    }

    /**
     * Редатироование в гриде
     *
     * @return mixed
     */
    protected function xEditable()
    {
        return [
            'class' => XEditableColumn::class,
            'url' => $this->getEditableUrl(),
            'format' => 'raw',
        ];
    }

    /**
     * Редатироование в гриде
     *
     * @return mixed
     */
    protected function linkUpdate()
    {
        return [
            'class' => UpdateColumn::class,
            'route' => $this->updateAction,
            'format' => 'raw',
        ];
    }

    /**
     * Создает url для x-editable
     *
     * @return mixed
     */
    public function getEditableUrl()
    {
        return Yii::$app->urlManager->createUrl(Yii::$app->controller->uniqueId . '/' . $this->editableAction);
    }

    /**
     * Конфигурация детального просмотра по умолчанию
     *
     * @return array
     */
    protected function defaultView()
    {
        $view['attribute'] = $this->attr;
        $view['label'] = $this->title;

        return $view;
    }

    /**
     * Конфигурация поля для детального просмотра
     *
     * @return array
     */
    protected function view()
    {
        return $this->defaultView();
    }

    /**
     * Результирующая конфигурация поля для детального просмотра
     *
     * @return array
     */
    final public function getView(): array
    {
        return ArrayHelper::merge($this->view(), $this->viewOptions);
    }

    /**
     * Правила валидации
     *
     * @return array|bool
     */
    public function rules()
    {
        $rules = [];
        if ($this->isSafe) {
            $rules[] = [$this->attr, 'safe'];
        }
        if ($this->isRequired) {
            $rules[] = [$this->attr, 'required', 'except' => ActiveRecord::SCENARIO_SEARCH];
        }
        if ($this->defaultValue !== null) {
            $rules[] = [$this->attr, 'default', 'value' => $this->defaultValue, 'except' => [ActiveRecord::SCENARIO_SEARCH]];
        }

        foreach ($this->rules AS $name => $option) {

            // 'lang', 'unique', 'targetAttribute' => ['id', 'lang']
            $options[0] = $this->attr;
            if (!is_int($name)) {
                $options[1] = $name;
                if (is_array($option)) {
                    foreach ($option as $key => $value) {
                        $options[$key] = $value;
                    }
                } else {
                    $options[] = $option;
                }
            } else {
                $options[1] = $option;
            }

            $rules[] = $options;
        }

        return $rules;
    }

    /**
     * Поведения
     *
     * @return array
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * Возвращает массив данных ассоциированных с полем
     *
     * @return array
     */
    public function getDataValue()
    {
        if ($this->_dataValue === null) {
            $func = $this->data;
            $this->_dataValue = is_callable($func) ? $func() : [];
        }

        return $this->_dataValue;
    }

    /**
     * Поиск
     *
     * @param ActiveQuery $query запрос
     */
    protected function search(ActiveQuery $query)
    {
        if ($this->model->hasAttribute($this->attr)) {
            $model = $this->model;
            $table = $model::tableName();
            $attr = $this->attr;
            $query->andFilterWhere(["$table.$attr" => $this->model->{$this->attr}]);
        }
    }

    /**
     * Накладывает ограничение на поиск
     *
     * @param ActiveQuery $query
     */
    public function applySearch(ActiveQuery $query)
    {
        if ($this->queryModifier) {
            call_user_func($this->queryModifier, $query, $this);
        } else {
            $this->search($query);
        }
    }

    /**
     * Возвращает подпись атрибута
     *
     * @return array
     */
    public function getAttributeLabel()
    {
        return [$this->attr => $this->title];
    }

    /**
     * @return string
     */
    public function getLoadUrl()
    {
        return is_array($this->loadUrl) ? Url::to($this->loadUrl) : $this->loadUrl;
    }
}
