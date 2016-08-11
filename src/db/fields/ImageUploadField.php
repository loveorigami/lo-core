<?php

namespace lo\core\db\fields;

use lo\core\behaviors\upload\UploadImage;
use lo\core\db\ActiveRecord;
use lo\core\inputs\ImageUploadInput;
use yii\helpers\ArrayHelper;

/**
 * Class ImageUploadField
 * Для загрузки изображений
 * @package lo\core\db\fields
 */
class ImageUploadField extends FileUploadField
{

    /** @var string|array имя класс, либо конфигурация компонента который рендерит поле вывода формы */
    public $inputClass = ImageUploadInput::class;

    /** @var string расширения */
    public $extensions = ['jpg, jpeg, gif, png'];

    /**
     * @return array
     */
    public function behaviors()
    {
        $parent = parent::behaviors();

        $code = self::BEHAVIOR_PREF . ucfirst($this->attr);

        $parent[$code] = ArrayHelper::merge([
            'class' => UploadImage::class,
            'attribute' => $this->attr,
            'scenarios'=>[ActiveRecord::SCENARIO_INSERT, ActiveRecord::SCENARIO_UPDATE],
            'path' => $this->getStoragePath().'/{type.slug}',
            'url' => $this->getStorageUrl().'/{type.slug}',
            'thumbPath' => $this->getStoragePath().'/{type.slug}/thumb',
            'thumbUrl' => $this->getStoragePath().'/{type.slug}/thumb',
            'generateNewName' => true,
            'thumbs' => [
                'thumb' => ['width' => 400, 'quality' => 90],
                'preview' => ['width' => 200, 'height' => 200],
            ],
        ], $this->uploadOptions);

        return $parent;
    }

}