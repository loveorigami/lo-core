<?php

namespace lo\core\db\fields;

use lo\core\behaviors\upload\UploadImage;
use lo\core\db\ActiveRecord;
use lo\core\inputs\ImageUploadInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class ImageUploadField
 * Для загрузки изображений
 * @package lo\core\db\fields
 */
class ImageUploadField extends ImageField
{
    /** Преффикс поведения */
    const BEHAVIOR_PREF = "upload";

    /** @var string|array имя класс, либо конфигурация компонента который рендерит поле вывода формы */
    public $inputClass = ImageUploadInput::class;

    /** @var string расширения */
    public $extensions = 'jpeg, jpg, png, gif';

    /** @var integer макс. размер файла 2Мб */
    public $maxSize = 2097152;

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
            'class' => UploadImage::class,
            'attribute' => $this->attr,
            'scenarios' => [ActiveRecord::SCENARIO_INSERT, ActiveRecord::SCENARIO_UPDATE],
            'path' => $this->getStoragePath(),
            'url' => $this->getStorageUrl(),
            'thumbPath' => $this->getStoragePath() . '/thumb',
            'thumbUrl' => $this->getStorageUrl() . '/thumb',
            'generateNewName' => true,
            'thumbs' => [
                'thumb' => ['width' => 100, 'height' => 75, 'quality' => 90],
                'big' => ['width' => 240, 'height' => 180],
            ],
            'createThumbsOnSave' => false,
            'createThumbsOnRequest' => true
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
            'image',
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
     * @param UploadImage $model
     * @return string
     */
    protected function getGridValue($model)
    {
        if ($this->relationName && $this->relationAttr) {
            if ($this->getRelationModel()->hasAttribute($this->relationAttr)) {
                $src = $model->{$this->relationName}->getThumbUploadUrl($this->relationAttr, 'thumb');
            } else {
                return null;
            }
        } else {
            $src = $model->getThumbUploadUrl($this->attr, 'thumb');
        }

        return Html::img($src);
    }
}