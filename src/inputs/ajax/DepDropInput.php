<?php

namespace lo\core\inputs\ajax;

use kartik\depdrop\DepDrop;
use lo\core\db\fields\ajax\DropDownField;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class DepDropInput
 * Выпадающий зависимый список
 * @see DropDownField
 * ```php
 *  "cat_id" => [
 *      "definition" => [
 *          "class" => fields\ajax\DropDownField::class,
 *          "loadUrl" => ['/module/category/list'], // ajax action
 *          "title" => Yii::t('backend', 'Category'),
 *          "inputClassOptions" => [
 *              'options' => [
 *                  'id' => 'cat_id'
 *              ]
 *          ],
 *          "relationName" => 'item',
 *      ],
 *      "params" => [$this->owner, "cat_id"]
 *  ],
 *
 *  "subcat_id" => [
 *      "definition" => [
 *          "class" => fields\HasOneField::class,
 *          'loadUrl' => ['/base/subcategory/list']
 *          "inputClass" => inputs\DepDropInput::class,
 *          "inputClassOptions" => [
 *              "widgetOptions" => [
 *                  'type' => inputs\DepDropInput::TYPE_SELECT2,
 *                  'data' => $this->getCategories(),
 *                  'pluginOptions' => [
 *                      'depends' => ['cat_id'], // html-id
 *                      'placeholder' => 'Выберите под-категорию...',
 *                  ]
 *              ]
 *          ],
 *      ],
 *      "params" => [$this->owner, "subcat_id"]
 *  ]
 * ```
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class DepDropInput extends AjaxInput
{
    const TYPE_DEFAULT = DepDrop::TYPE_DEFAULT;
    const TYPE_SELECT2 = DepDrop::TYPE_SELECT2;

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

        $widgetOptions = ArrayHelper::merge(
            $this->defaultWidgetOptions(),
            $this->widgetOptions,
            ["options" => $options]
        );

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(DepDrop::class, $widgetOptions);
    }

    /**
     * @return array
     */
    protected function defaultWidgetOptions()
    {
        return [
            'type' => self::TYPE_DEFAULT,
            'data' => [],
            'pluginOptions' => [
                'depends' => [],
                'placeholder' => 'Select...',
                'url' => $this->getLoadUrl()
            ]
        ];
    }
}