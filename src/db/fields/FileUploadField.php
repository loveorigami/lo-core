<?php

namespace lo\core\db\fields;

use lo\core\behaviors\upload\Upload;
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

    /**
     * @return array
     */
    public function behaviors()
    {
        $parent = parent::behaviors();

        $code = self::BEHAVIOR_PREF . ucfirst($this->attr);

        $parent[$code] = ArrayHelper::merge([
            'class' => Upload::class,
            'attributeName' => $this->attr,
            //'path' => '@storage/qwee/1',
            //'url' => '@storageUrl/qwee/1',
            'savePath' => '@storage/qwee/1',
           'generateNewName' => true,
           'protectOldValue' => true,
/*            'thumbs' => [
                'thumb' => ['width' => 400, 'quality' => 90],
                'preview' => ['width' => 200, 'height' => 200],
            ],*/
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

        $rules[] = [$this->attr, 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => [ActiveRecord::SCENARIO_INSERT, ActiveRecord::SCENARIO_UPDATE]];

        return $rules;
    }
}