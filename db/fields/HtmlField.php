<?php

namespace lo\core\db\fields;

/**
 * Class HtmlField
 * Поле WYSIWYG редактора. Использует CKEditor
 * @package lo\core\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class HtmlField extends TextAreaField
{

    /**
     * @inheritdoc
     */
    public $inputClass = '\lo\core\inputs\HtmlInput';

}