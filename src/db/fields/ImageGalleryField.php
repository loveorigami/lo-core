<?php

namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;
use lo\core\inputs;
use lo\modules\gallery\behaviors\GalleryImageBehavior;
use yii\helpers\ArrayHelper;

/**
 * Class ImageGalleryField
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ImageGalleryField extends ImageField
{
    /** @var string|array имя класс, либо конфигурация компонента который рендерит поле вывода формы */
    public $inputClass = inputs\ImageGalleryInput::class;

    /** @var array настройки поведения */
    public $uploadOptions = [];

    /** @var string название поведения */
    public $galleryBehavior;

    /** @var string сущность */
    public $entity;

    /**
     * @return array
     */
    public function behaviors()
    {
        if ($this->relationAttr) {
            return [];
        }

        $parent = parent::behaviors();
        $code = $this->galleryBehavior;
        $model = $this->model;

        $parent[$code] = ArrayHelper::merge([
            'class' => GalleryImageBehavior::class,
            'scenarios' => [ActiveRecord::SCENARIO_UPDATE],
            'attribute' => $this->attr,
            'path' => $this->getStoragePath(),
            'url' => $this->getStorageUrl(),
            'thumbs' => [
                self::THUMB => ['width' => 75, 'height' => 50, 'quality' => 90],
            ],
            'entity' => $model::getEntityName(),
            'unlinkOnSave' => false,
            'createThumbsOnSave' => true,
            'createThumbsOnRequest' => true
        ], $this->uploadOptions);

        return $parent;
    }

}