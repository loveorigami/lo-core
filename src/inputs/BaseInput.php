<?php
namespace lo\core\inputs;

use yii\base\InvalidConfigException;
use yii\base\Object;
use yii\widgets\ActiveForm;
use \lo\core\db\fields\BaseField;

/**
 * Class BaseInput
 * Базовый класс полей ввода форм
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class BaseInput extends Object
{
    /** @var BaseField поле модели */
    public $modelField;

    /** @var array html атрибуты */
    public $options = [];

    /** @var array парамеиры виджета */
    public $widgetOptions = [];


    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */
    abstract public function renderInput(ActiveForm $form, Array $options = [], $index = false);

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (empty($this->modelField) OR !$this->modelField instanceof BaseField)
            throw new InvalidConfigException("Property 'modelField' must be instance of " . BaseField::class);
    }

    /**
     * Возвращает имя атрибута для поля формы
     * @param bool|int $index индекс модели при табличном вводе
     * @param string $attr атрибут
     * @return string
     */
    protected function getFormAttrName($index, $attr)
    {
        return ($index !== false) ? "[$index]$attr" : $attr;
    }

    /**
     * Возвращает модель для формы с учетом наличия relation
     * @return \lo\core\db\ActiveRecord|mixed
     */
    public function getModel()
    {
        $model = $this->modelField->model;
        $relation = $this->modelField->relationName;

        if ($relation && $model->scenario != $model::SCENARIO_SEARCH) {
            return $this->modelField->getRelationModel();
        }

        return $model;
    }

    /**
     * Возвращает имя атрибута для поля формы с учетом наличия relation
     * @return string
     */
    public function getAttr()
    {
        $attr = $this->modelField->attr;
        $relationAttr = $this->modelField->relationAttr;
        $model = $this->modelField->model;

        if ($relationAttr && $model->scenario != $model::SCENARIO_SEARCH) {
            return $relationAttr;
        }

        return $attr;
    }

} 