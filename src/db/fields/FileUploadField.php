<?php

namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;
use lo\core\inputs\FileUploadInput;
use Yii;

/**
 * Class FileUploadField
 * Поле для загрузки файлов
 * @package lo\core\db\fields
 */
class FileUploadField extends FileField
{
    /** @var string|array имя класс, либо конфигурация компонента который рендерит поле вывода формы */
    public $inputClass = FileUploadInput::class;

}