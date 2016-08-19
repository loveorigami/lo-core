<?php

namespace lo\core\db\fields;

use lo\core\behaviors\upload\UploadFile;
use lo\core\db\ActiveRecord;
use lo\core\inputs\FileUploadInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
        if ($this->relationAttr) {
            return [];
        }

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
        if ($this->relationAttr) {
            return [];
        }

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

    /**
     * Вывод значения в гриде с учетом связи
     * @param UploadFile $model
     * @return string
     */
    protected function getGridValue($model)
    {
        if ($this->relationName && $this->relationAttr) {
            if ($this->getRelationModel()->hasAttribute($this->relationAttr)) {
                $src = $model->{$this->relationName}->getUploadUrl($this->relationAttr);
            } else {
                return null;
            }
        } else {
            $src = $model->getUploadUrl($this->attr);
        }

        return Html::a('<span class="fa fa-download"></span>', $src);
    }
}