<?php

namespace lo\core\modules\main\widgets\includes;

use lo\core\modules\main\models\IncludeItem as Model;
use lo\core\widgets\App;

/**
 * Class Includes
 * Виджет для вывода включаемых областей
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class IncludeItem extends App
{
    /** @var string символьный код включаемой области */
    public $code;

    /** @var IncludeItem $model модель включаемой области */
    public $model;

    /**
     * @return bool
     */
    public function init()
    {
        if (!$this->isShow()) {
            return null;
        }

        if ($this->model === null)
            $this->model = Model::findByCode($this->code);
    }

    /**
     * @return bool|mixed|string
     */
    public function run()
    {
        if (!$this->isShow() OR empty($this->model)) {
            return false;
        }

        if (!empty($this->model->file)) {
            return $this->view->renderFile($this->model->file);
        } else {
            return $this->model->text;
        }
    }
}