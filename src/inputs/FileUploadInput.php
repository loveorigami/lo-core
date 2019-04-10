<?php

namespace lo\core\inputs;

use kartik\file\FileInput;
use lo\core\helpers\PkHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * Class FileUploadInput
 * Поле ввода диапазона дат
 *
 * @package lo\core\inputs
 */
class FileUploadInput extends BaseInput
{
    /**
     * Опции по умолчанию
     *
     * @var array
     */
    protected $defaultOptions = [
        'language' => 'ru',
        'pluginOptions' => [
            'showRemove' => false,
            'showUpload' => false,
        ],
        'options' => [
            'multiple' => false,
        ],
    ];

    public $delBtn = true;

    /**
     * Формирование Html кода поля для вывода в форме
     *
     * @param ActiveForm $form    объект форма
     * @param array      $options массив html атрибутов поля
     * @param bool|int   $index   индекс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false): string
    {
        $model = $this->getModel();
        $attr = $this->getAttr();
        $filename = $model->$attr;

        $initFile = [];
        $file = $model->getUploadUrl($this->getAttr());

        if ($file && $model->scenario !== $model::SCENARIO_INSERT) {
            $initFile = ArrayHelper::merge($this->defaultOptions, [
                'pluginOptions' => [
                    'initialPreview' => [
                        $file,
                    ],
                    'overwriteInitial' => true,
                    'initialPreviewAsData' => true,
                ],
            ]);
        }

        $options = ArrayHelper::merge($this->options, $options);
        $widgetOptions = ArrayHelper::merge(
            $this->defaultOptions,
            $this->widgetOptions,
            ['options' => $options],
            $initFile
        );

        //$label = $this->getModel()->getAttributeLabel($attr);
        //$label = $label . ' (' . $filename . ')';


        $str [] = $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))
            ->widget(FileInput::class, $widgetOptions);

        if ($filename && !$model->errors && $this->delBtn) {
            $str [] = Html::a(
                Yii::t('backend', 'Remove file - {file}', ['file' => $filename]),
                Url::to([
                    'delete-file',
                    'id' => PkHelper::encode($this->getModel()),
                    'f' => $attr,
                ]),
                [
                    'class' => 'btn btn-danger',
                    'data-method' => 'post',
                ]
            );
        }

        return implode('', $str);
    }
}
