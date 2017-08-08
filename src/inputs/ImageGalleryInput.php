<?php

namespace lo\core\inputs;


use lo\core\db\fields\ImageGalleryField;
use lo\modules\gallery\modules\admin\widgets\GalleryInput;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class ImageUploadInput
 * @package lo\core\inputs
 * @property \lo\core\db\fields\ImageUploadField $modelField
 */
class ImageGalleryInput extends BaseInput
{
    /**
     * Опции по умолчанию
     * @var array
     */
    protected $defaultOptions = [
        'apiRoute' => 'gallery',
    ];

    /** @var string размер превью */
    public $thumSize = 'big';

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index индекс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $options);
        $model = $this->getModel();

        /** @var ImageGalleryField $modelField */
        $modelField = $this->modelField;

        $widgetOptions = ArrayHelper::merge(
            $this->defaultOptions,
            $this->widgetOptions,
            ["options" => $options],
            [
                'model' => $model,
                'attribute' => $this->getAttr(),
                'galleryBehavior' => $modelField->galleryBehavior,
            ]
        );
        if ($model->isNewRecord) {
            return 'Can not upload images for new record';
        } else {
            return GalleryInput::widget($widgetOptions);
        }
    }
}