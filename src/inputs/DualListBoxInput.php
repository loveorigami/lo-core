<?php

namespace lo\core\inputs;

use softark\duallistbox\DualListbox;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class DualListBoxInput
 *
 * @package lo\core\inputs
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class DualListBoxInput extends BaseInput
{
    /**
     * Настройки по умолчанию
     *
     * @var array
     */
    protected $defaultOptions = [
        'options' => [
            'multiple' => true,
            'size' => 20,
        ],
        'clientOptions' => [
            'moveOnSelect' => false,
            'filterTextClear' => 'Показать все',
            'filterPlaceHolder' => 'Фильтр',
            'moveSelectedLabel' => 'Перенести выбранные',
            'moveAllLabel' => 'Перенести все',
            'removeSelectedLabel' => 'Удалить выбранные',
            'removeAllLabel' => 'Удалить все',
            'selectedListLabel' => '<span class="text text-primary">Выбрано</span>',
            'nonSelectedListLabel' => '<span class="text text-primary">Доступно</span>',
            'infoText' => 'Всего {0}',
            'infoTextFiltered' => '<span class="label label-warning">Показано</span> {0} из {1}',
            'infoTextEmpty' => 'Выбрано 0',
        ],
    ];

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
        $data = $this->modelField->getDataValue();

        if (empty($data)) {
            return false;
        }

        $options = ArrayHelper::merge(
            $this->defaultOptions,
            $this->widgetOptions,
            $options, ['items' => $data]
        );

        return $form->field($this->getModel(), $this->getFormAttrName($index, $this->getAttr()))->widget(DualListbox::class, $options);
    }
}
