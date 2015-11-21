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
    /**
     * @inheritdoc
     */

    /**
     * Размер по умолчанию для превью изображений в гриде и при детальном просмотре
     */
    const DEFAULT_SIZE = 80;

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
     * @var array html атрибуты превью изображений
     */
    public $imageOptions = [];


    /**
     * Возвращает html тег изображения. Производит ресайз
     * @param string $path путь к файлу
     * @param int $width ширина изображения
     * @param int $height высота изображения
     * @return string
     */
    protected function renderImageTag($img, $width, $height)
    {
      $path = Yii::getAlias($this->webroot) . $img;
      $src = Yii::getAlias($this->webroot.'Url') . $img;

      if (!is_file($path) OR !FileHelper::isImage($path))
        return $path;

        $options = array_merge([
            "src" => $src,
            "class" => "img-thumbnail detail-img",
            "width" =>$this->gridWidth
        ], $this->imageOptions);

        return Html::tag('img', '', $options);

    }

    /**
     * @inheritdoc
     */
    protected function renderFilesGridView($img)
    {
        return $this->renderImageTag($img, $this->gridWidth, $this->gridHeight);
    }

}