<?php

namespace lo\core\db\fields;

use lo\core\behaviors\upload\UploadFile;
use lo\core\db\ActiveRecord;
use lo\core\inputs\FileUploadInput;
use yii\helpers\ArrayHelper;

/**
 * Class FileUploadField
 * Поле для загрузки файлов
 * @package lo\core\db\fields
 */
class FileUploadField extends FileField
{
    /** Преффикс поведения */
    const BEHAVIOR_PREF = "upload";

    /** @var array настройки поведени */
    public $uploadOptions = [];

    /** @var string|array имя класс, либо конфигурация компонента который рендерит поле вывода формы */
    public $inputClass = FileUploadInput::class;

    /** @var integer макс. размер файла 2Мб */
    public $maxSize = 2097152;

    /** @var string расширения */
    public $extensions = ['pdf, rar'];

    /**
     * @return array
     */
    public function behaviors()
    {
        $parent = parent::behaviors();

        $code = self::BEHAVIOR_PREF . ucfirst($this->attr);

        $parent[$code] = ArrayHelper::merge([
            'class' => UploadFile::class,
            'attribute' => $this->attr,
            'scenarios' => [ActiveRecord::SCENARIO_INSERT, ActiveRecord::SCENARIO_UPDATE],
            'path' => $this->getStoragePath(),
            'url' => $this->getStorageUrl(),
            'generateNewName' => true,
        ], $this->uploadOptions);

        return $parent;
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            $this->attr,
            'file',
            'extensions' => $this->extensions,
            'on' => [
                ActiveRecord::SCENARIO_INSERT,
                ActiveRecord::SCENARIO_UPDATE
            ],
            'maxSize' => $this->maxSize
        ];

        return $rules;
    }

}