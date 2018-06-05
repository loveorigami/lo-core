<?php

namespace lo\core\inputs;

use lo\modules\eavb\widgets\Attributes;
use yii\widgets\ActiveForm;

/**
 * Class HtmlInput
 * Html поле
 * @package lo\core\inputs
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class EavInput extends BaseInput
{
    /**
     * @param ActiveForm $form
     * @param array $options
     * @param bool $index
     * @return string
     * @throws \Exception
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {
        return Attributes::widget([
            'form' => $form,
            'model' => $this->getModel(),
        ]);
    }


} 