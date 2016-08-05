<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use lo\modules\eav\models\EavAttribute;

/**
 * Class HtmlInput
 * Html поле
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class EavInput extends BaseInput {

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @return string
     */


    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        $options = ArrayHelper::merge($this->options, $options);
        $model = $this->modelField->model;
        //var_dump($model->getEavAttributeValue('eav_name'));

        //var_dump($model->hasEavAttribute('eav_name'));

        return $form->field($model, 'eav_name')->textInput(['maxlength' => true]);
    }


} 