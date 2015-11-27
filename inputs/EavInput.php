<?php

namespace lo\core\inputs;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class HtmlInput
 * Html поле
 * @package lo\core\inputs
 * @author Churkin Anton <webadmin87@gmail.com>
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
        $field = '';
        foreach($this->modelField->model->getEavAttributes()->all() as $attr){
            $field.= $form->field($this->modelField->model, $attr->name, ['class' => '\lo\modules\eav\widgets\ActiveField'])->eavInput();
        }
        return $field;
    }


} 