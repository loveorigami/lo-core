<?php

namespace lo\core\db\fields;

use lo\core\inputs\ElfinderFileInput;
use Yii;

/**
 * Class FileField
 * Поле для загрузки файлов
 * @package lo\core\db\fields
 */
class FileField extends BaseField
{
    /** @var bool отображать в гриде */
    public $showInGrid = false;

    /** @var bool отображать при детальном просмотре */
    public $showInView = true;

    /** @var bool отображать в форме */
    public $showInForm = true;

    /** @var bool отображать в фильтре грида */
    public $showInFilter = false;

    /** @var bool отображать в расширенном фильре */
    public $showInExtendedFilter = false;

    /** @var bool обязательно ли поле к заполнению */
    public $isRequired = false;

    /** @var string|array имя класс, либо конфигурация компонента который рендерит поле вывода формы */
    public $inputClass = ElfinderFileInput::class;

    /** @var string путь для файлового хранилища */
    protected $_storagePath;

    /** @var string Url для файлового хранилища */
    protected $_storageUrl;

    /**
     * @inheritdoc
     */
    public function grid()
    {
        $grid = parent::grid();
        $grid['format'] = 'html';
        $grid['value'] = function ($model) {
            return $this->renderFileView($model->{$this->attr});
        };

        return $grid;
    }

    /**
     * @inheritdoc
     */
    protected function view()
    {
        $view = parent::view();
        if (is_array($this->model->{$this->attr})) {
            $view["value"] = $this->renderFileView($this->model->{$this->attr});
            $view["format"] = "html";
        }
        return $view;
    }

    /**
     * Возвращает строку для отображения файла при детальном просмотре
     * @param string $file
     * @return string
     */
    protected function renderFileView($file)
    {
        if (!$file) return '';
        return '<a href="' . $this->getStorageUrl() . $file . '"><span class="fa fa-download"></span></a>' . "\n";
    }

    /**
     * @inheritdoc
     */
    protected function defaultGridFilter()
    {
        return false;
    }

    /**
     * @return bool|string
     */
    public function getStoragePath()
    {
        if (!$this->_storagePath) {
            $this->_storagePath = Yii::getAlias('@storage');
        }
        return $this->_storagePath;
    }

    /**
     * @param $storagePath
     */
    public function setStoragePath($storagePath)
    {
        $this->_storagePath = $storagePath;
    }

    /**
     * @return bool|string
     */
    public function getStorageUrl()
    {
        if (!$this->_storageUrl) {
            $this->_storageUrl = Yii::getAlias('@storageUrl');
        }
        return $this->_storageUrl;
    }

    /**
     * @param $storageUrl
     */
    public function setStorageUrl($storageUrl)
    {
        $this->_storageUrl = $storageUrl;
    }

}