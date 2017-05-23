<?php

namespace lo\core\db\fields;

use lo\core\helpers\DateHelper;
use lo\core\inputs\DateTimeInput;

/**
 * Class DateTimeField
 * Поле ввода даты
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class DateTimeField extends DateField
{
    /**
     * @var string формат даты
     */
    public $dateFormat = DateHelper::DB_DATETIME_FORMAT;

    /**
     * @inheritdoc
     */
    public $inputClass = DateTimeInput::class;

}