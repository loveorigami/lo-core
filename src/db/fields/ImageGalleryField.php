<?php

namespace lo\core\db\fields;

use lo\core\inputs;
use lo\modules\gallery\behaviors\GalleryImageBehavior;
use lo\modules\gallery\models\GalleryItem;
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

    /**
     * @return array
     */
    public function behaviors()
    {
        $parent = parent::behaviors();
        $code = $this->galleryBehavior;
        $model = $this->model;

        $parent[$code] = ArrayHelper::merge([
            'class' => GalleryImageBehavior::class,
            'modelClass' => GalleryItem::class,
            'owner' => $model,
            'entity' => $model::getEntityName(),
            'scenarios' => [$model::SCENARIO_UPDATE],
            'attribute' => $this->attr,
            'extensions' => 'jpeg, jpg, png, gif',
            'maxSize' => 1024 * 1024 * 2,
            'path' => $this->getStoragePath(),
            'url' => $this->getStorageUrl(),
            'thumbs' => [
                self::THUMB => ['width' => 75, 'height' => 50, 'quality' => 90],
            ],
            'removeDirectoryOnDelete' => false,
            'createThumbsOnSave' => true,
            'createThumbsOnRequest' => true
        ], $this->uploadOptions);

        return $parent;
    }

}