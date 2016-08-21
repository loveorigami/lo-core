<?php

namespace lo\core\db\fields;

use lo\core\behaviors\upload\UploadPathImage;
use lo\core\inputs;
use yii\helpers\ArrayHelper;

/**
 * Class ImagePathField
 * Для загрузки изображений
 *  "image" => [
 *      "definition" => [
 *          "class" => fields\ImagePathField::class,
 *          "title" => Yii::t('backend', 'Image'),
 *          "initValue" => '/'.self::PATH.'/manager-none.jpg',
 *          'uploadOptions' => [
 *              'path' => '@storagePath/'.self::PATH,
 *              'url' => '@storageUrl/'.self::PATH,
 *              // для генирации thumbs, если нужно
 *              'thumbPath' => '@storagePath/'.self::PATH.'/thumb',
 *              'thumbUrl' => '@storageUrl/'.self::PATH.'/thumb',
 *              'thumbs' => [
 *                  'thumb' => ['width' => 100, 'height' => 75, 'quality' => 90],
 *                  'big' => ['width' => 280, 'height' => 210],
 *              ],
 *          ],
 *          "inputClassOptions" => [
 *              "widgetOptions" => [
 *                  'path' => self::PATH
 *              ],
 *          ],
 *      ],
 *      "params" => [$this->owner, "image"]
 *  ],
 * @package lo\core\db\fields
 */
class ImagePathField extends ImageField
{
    /** @var string|array имя класс, либо конфигурация компонента который рендерит поле вывода формы */
    public $inputClass = inputs\ElfinderImageInput::class;

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
            'class' => UploadPathImage::class,
            'attribute' => $this->attr,
            'path' => $this->getStoragePath(),
            'url' => $this->getStorageUrl(),
            'thumbs' => [
                self::THUMB => ['width' => 75, 'height' => 50, 'quality' => 90],
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
            'filter', 'filter' => function ($value) {
                return basename($value);
            }
        ];

        return $rules;
    }

}