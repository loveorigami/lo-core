<?php

namespace lo\core\db\fields;

use lo\core\behaviors\upload\IUploadFile;
use lo\core\inputs\ElfinderFileInput;
use Yii;
use yii\helpers\Html;

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

    /** @var string путь к файлу от хранилища */
    public $path;

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
        $grid['label'] = 'File';
        $grid['format'] = 'raw';
        $grid['headerOptions'] = [
            'style' => 'width: 50 px;',
        ];
        return $grid;
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
            $this->_storagePath = Yii::getAlias('@storagePath');
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

    /**
     * Вывод значения в гриде с учетом связи
     * @param IUploadFile $model
     * @return string
     */
    protected function getGridValue($model)
    {
        $src = $model->getUploadUrl($this->attr);
        if ($src) {
            return Html::a('<span class="fa fa-download"></span>', $src, [
                'data-pjax' => 0,
                'target' => '_blank'
            ]);
        }
        return '';
    }
}