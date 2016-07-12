<?php

namespace lo\core\db\fields;

use Yii;
use lo\core\helpers\FileHelper;
use lo\core\inputs;

/**
 * Class ElfImgField
 * Для загрузки изображений через elfinder
 *  "image" => [
 *      "definition" => [
 *          "class" => fields\ElfImgField::class,
 *          "title" => Yii::t('backend', 'Image'),
 *          "initValue" => '/'.self::PATH.'/manager-none.jpg',
 *          "inputClassOptions" => [
 *              "widgetOptions" => [
 *                  'path' => self::PATH
 *              ],
 *          ],
 *      ],
 *      "params" => [$this->owner, "image"]
 *  ],
 * @package lo\core\db\fields
 */
class ElfImgField extends ElfFileField
{
    public $showInGrid = true;
    public $showInFilter = false;
    public $isRequired = false;
    public $editInGrid = false;
    public $showInExtendedFilter = false;

    public $inputClass = inputs\ElfImgInput::class;

    /**
     * Размер по умолчанию для превью изображений в гриде и при детальном просмотре
     */
    const DEFAULT_SIZE = 55;

    /**
     * @var int ширина изображения при детальном просмотре
     */
    public $viewWidth = 200;

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
            'style' => 'width: ' . $this->gridWidth . 'px;',
        ];
        $grid['value'] = function ($model, $width, $height) {
            return $this->renderFilesGridView($model->{$this->attr}, $width, $height);
        };
        return $grid;
    }

    /**
     * Возвращает html тег изображения. Производит ресайз
     * @param string $img путь к картинке
     * @param int $width высота изображения
     * @return string
     */
    protected function renderImageTag($img, $width, $height)
    {
        return FileHelper::storageImg($img, ['width' => $width]);
    }


    /**
     * @param array $img
     * @param int $width высота изображения
     * @param int $height высота изображения
     * @return string
     */
    protected function renderFilesGridView($img, $width, $height)
    {
        return $this->renderImageTag($img, $this->gridWidth, $this->gridHeight);
    }

}