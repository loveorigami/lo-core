<?php
namespace lo\core\db\fields;

use lo\core\inputs\HiddenInput;

/**
 * Class HiddenField
 * Скрытое поле модели.
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class HiddenField extends BaseField
{
	/**
	 * @var bool отображать в фильтре грида
	 */
	public $showInFilter = false;

	/**
	 * @var bool отображать в расширенном фильре
	 */
	public $showInExtendedFilter = false;

	/**
	 * @var bool отображать поле при табличном вводе
	 */
	public $showInTableInput = false;

    /**
     * @inheritdoc
     */
	public $inputClass = HiddenInput::class;
}