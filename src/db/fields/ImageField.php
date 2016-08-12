<?php

namespace lo\core\db\fields;

use lo\core\inputs;

/**
 * Class ImageField
 * Для загрузки изображений
 *  "image" => [
 *      "definition" => [
 *          "class" => fields\ImageField::class,
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
class ImageField extends FileField
{
    public $showInGrid = true;
    public $showInFilter = false;
    public $isRequired = false;
    public $editInGrid = false;
    public $showInExtendedFilter = false;

    public $inputClass = inputs\ElfinderImageInput::class;

    /** Размер по умолчанию для превью изображений в гриде и при детальном просмотре */
    const DEFAULT_SIZE = 55;

    /** @var int ширина изображения при детальном просмотре */
    public $viewWidth =self::DEFAULT_SIZE;

    /** @var int высота изображения при детальном просмотре */
    public $viewHeight = self::DEFAULT_SIZE;

    /** @var int ширина изображения в гриде */
    public $gridWidth = self::DEFAULT_SIZE;

    /** @var int высота изображения в гриде */
    public $gridHeight = self::DEFAULT_SIZE;

    /**
     * @inheritdoc
     */
    public function grid()
    {
        $grid = parent::grid();
        $grid['label'] = 'Img';
        $grid['headerOptions'] = [
            'style' => 'width: ' . $this->gridWidth . 'px;',
        ];
        return $grid;
    }

}