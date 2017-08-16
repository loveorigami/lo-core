<?php

namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;
use lo\core\interfaces\IUploadImage;
use yii\helpers\Html;

/**
 * Class ImageField
 * Общий класс изображений
 * @package lo\core\db\fields
 */
class ImageField extends FileField
{
    public $showInGrid = true;
    public $showInFilter = false;
    public $isRequired = false;
    public $editInGrid = false;
    public $showInExtendedFilter = false;

    /** префикс поведения */
    const BEHAVIOR_PREF = 'img';

    /** Размер по умолчанию для превью изображений в гриде и при детальном просмотре */
    const DEFAULT_SIZE = 50;

    /** Префикс иконки */
    const THUMB = 'tmb';

    /** @var int ширина изображения при детальном просмотре */
    public $viewWidth = self::DEFAULT_SIZE;

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

    /**
     * Вывод значения в гриде с учетом связи
     * @param ActiveRecord $model
     * @return string
     */
    protected function getGridValue($model)
    {
        /** @var IUploadImage| $behavior */
        $behavior = $model->getBehavior($this->getBehaviorName());
        $src = $behavior->getThumbUploadUrl($this->attr, self::THUMB);
        return Html::img($src, ['width' => $this->gridWidth]);
    }

    /**
     * @return string
     */
    public function getBehaviorName(){
        return static::BEHAVIOR_PREF . ucfirst($this->attr);
    }
}