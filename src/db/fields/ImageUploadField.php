<?php

namespace lo\core\db\fields;

use lo\core\behaviors\upload\UploadImage;
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
            'storagePath' => $this->getStoragePath(),
            'storageUrl' => $this->getStorageUrl(),
            'thumbPath' => $this->path,
            'generateNewName' => true,
        ], $this->uploadOptions);

        return $parent;
    }

}