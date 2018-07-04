<?php

namespace lo\core\inputs;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\web\AssetBundle;
use yii\widgets\ActiveForm;
use \lo\core\db\fields\BaseField;

/**
 * Class BaseInput
 * Базовый класс полей ввода форм
 *
 * @package lo\core\inputs
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class BaseInput extends BaseObject
{
    /** @var BaseField поле модели */
    public $modelField;

    /** @var array html атрибуты */
    public $options = [];

    /** @var array парамеиры виджета */
    public $widgetOptions = [];

    /**
     * @var string
     */
    public $assetClass;

    /** @var string */
    public $field;

    /**
     * Формирование Html кода поля для вывода в форме
     *
     * @param ActiveForm $form    объект форма
     * @param array      $options массив html атрибутов поля
     * @param bool|int   $index   индекс модели при табличном вводе
     * @return string
     */
    abstract public function renderInput(ActiveForm $form, Array $options = [], $index = false);

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (null === $this->modelField || !$this->modelField instanceof BaseField) {
            throw new InvalidConfigException("Property 'modelField' must be instance of " . BaseField::class);
        }

        $this->registerAsset();
    }

    /**
     * Возвращает имя атрибута для поля формы
     *
     * @param bool|int $index индекс модели при табличном вводе
     * @param string   $attr  атрибут
     * @return string
     */
    protected function getFormAttrName($index, $attr): string
    {
        return ($index !== false) ? "[$index]$attr" : $attr;
    }

    /**
     * Возвращает модель для формы с учетом наличия relation
     *
     * @return \lo\core\db\ActiveRecord|mixed
     */
    public function getModel()
    {
        return $this->modelField->model;
    }

    /**
     * Возвращает имя атрибута для поля формы с учетом наличия relation
     *
     * @return string
     */
    public function getAttr(): string
    {
        return $this->modelField->attr;
    }

    /**
     * register assets
     */
    public function registerAsset()
    {
        if ($this->assetClass) {
            /** @var AssetBundle $assetClass */
            $assetClass = $this->assetClass;
            $view = Yii::$app->getView();
            $assetClass::register($view);
        }
    }
} 
