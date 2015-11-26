<?php

namespace lo\core\db\fields;

use Yii;
use lo\core\helpers\FileHelper;
use yii\helpers\Html;

/**
 * Class HtmlField
 * Поле WYSIWYG редактора. Использует CKEditor
 * @package lo\core\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class ElfImgField extends ElfFileField
{
    public $showInGrid = true;
    public $showInFilter = false;
    public $isRequired = false;
    public $editInGrid = false;
    public $showInExtendedFilter = false;

    public $inputClass = '\lo\core\inputs\ElfImgInput';
    /**
     * Размер по умолчанию для превью изображений в гриде и при детальном просмотре
     */
    const DEFAULT_SIZE = 50;

    /**
     * @var int ширина изображения при детальном просмотре
     */
    public $viewWidth = self::DEFAULT_SIZE;

    /**
     * @var int высота изображения при детальном просмотре
     */
    public $viewHeight = self::DEFAULT_SIZE;

    /**
     * @var int ширина изображения в гриде
     */
    public $gridWidth = self::DEFAULT_SIZE;

    /**
     * @var int высота изображения в гриде
     */
    public $gridHeight = self::DEFAULT_SIZE;


    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = parent::grid();
        $grid['format'] = 'html';
        $grid['label'] = 'Img';
        $grid['headerOptions'] = [
            'style' => 'width: 55px;',
        ];
        $grid['value'] = function ($model, $index, $widget) {
            return $this->renderFilesGridView($model->{$this->attr});
        };
        return $grid;
    }

    /**
     * Возвращает html тег изображения. Производит ресайз
     * @param string $path путь к файлу
     * @param int $width ширина изображения
     * @param int $height высота изображения
     * @return string
     */
    protected function renderImageTag($img, $width, $height)
    {
        return FileHelper::storageImg($img, ['width'=>$width]);
    }

    /**
     * @inheritdoc
     */
    protected function renderFilesGridView($img)
    {
        return $this->renderImageTag($img, $this->gridWidth, $this->gridHeight);
    }

}