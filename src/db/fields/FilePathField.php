<?php

namespace lo\core\db\fields;

use lo\core\behaviors\upload\UploadPathFile;
use lo\core\inputs;
use yii\helpers\ArrayHelper;

/**
 * Class FilePathField
 * Поле для загрузки файлов
 * @package lo\core\db\fields
 */
class FilePathField extends FileField
{
    /** @var string|array имя класс, либо конфигурация компонента который рендерит поле вывода формы */
    public $inputClass = inputs\ElfinderFileInput::class;

    /** Преффикс поведения */
    const BEHAVIOR_PREF = "elfupload";

    /** @var array настройки поведени */
    public $uploadOptions = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        if ($this->relationAttr) {
            return [];
        }

        $parent = parent::behaviors();
        $code = self::BEHAVIOR_PREF . ucfirst($this->attr);
        $parent[$code] = ArrayHelper::merge([
            'class' => UploadPathFile::class,
            'attribute' => $this->attr,
            'path' => $this->getStoragePath(),
            'url' => $this->getStorageUrl(),
        ], $this->uploadOptions);

        return $parent;
    }

}