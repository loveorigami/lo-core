<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 17.07.2018
 * Time: 10:22
 */

namespace lo\core\widgets\awcheckbox\dto;


use yii\helpers\ArrayHelper;

/**
 * Class GroupDto
 *
 * @package lo\core\widgets\awcheckbox\dto
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class GroupDto
{
    protected $checked = [];
    protected $data;
    protected $gr = 1;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function isCheckbox()
    {
        return ArrayHelper::getValue($this->data, 'checkbox', false);
    }

    public function getName()
    {
        return ArrayHelper::getValue($this->data, 'name', 'aw');
    }

    public function getId(): string
    {
        return $this->getName() . $this->gr;
    }

    public function getValue(): string
    {
        return $this->gr . $this->getName();
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $def = ['class' => 'label-default', 'style' => 'padding:0 5px;'];
        $options = ArrayHelper::getValue($this->data, 'labelOptions', $def);

        return ArrayHelper::merge($def, $options);
    }

    /**
     * @return array
     */
    public function getCheckboxOptions(): array
    {
        $def = [
            'id' => $this->getId(),
            'class' => 'row',
            'value' => $this->getValue(),
        ];

        $options = ArrayHelper::getValue($this->data, 'checkboxOptions', $def);

        return ArrayHelper::merge($def, $options);
    }

    /**
     * @return array
     */
    public function getLabelOptions(): array
    {
        $def = [
            'for' => $this->getId(),
            'class' => 'label label-info',
        ];

        $options = ArrayHelper::getValue($this->data, 'labelOptions', $def);

        return ArrayHelper::merge($def, $options);
    }

    public function next()
    {
        $this->gr++;
    }

    /**
     * @param $checked
     */
    public function setCheckedData($checked)
    {
        $this->checked = $checked;
    }

    /**
     * @param $key
     * @return bool
     */
    public function isCheckedItem($key): bool
    {
        return \in_array($key, $this->checked, false);
    }

    /**
     * @return bool
     */
    public function isChecked(): bool
    {
        return \count($this->checked) > 0;
    }
}
