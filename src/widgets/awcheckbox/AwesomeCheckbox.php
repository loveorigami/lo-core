<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 13.07.2016
 * Time: 8:40
 */

namespace lo\core\widgets\awcheckbox;

use lo\core\widgets\awcheckbox\dto\GroupDto;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class AwesomeCheckbox extends InputWidget
{

    /**
     * Class AwesomeCheckbox
     *
     * @package bookin\awesome\checkbox
     *
     * @property boolean|string|array $checked
     * @property string               $type
     * @property array|string         $style
     * @property array                $wrapperOptions
     *
     * @property string               $labelId
     * @property string               $labelContent
     * @property string               $input
     * @property array                $list
     */

    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_RADIO = 'radio';
    const STYLE_DEFAULT = '';
    const STYLE_PRIMARY = 'primary';
    const STYLE_SUCCESS = 'success';
    const STYLE_INFO = 'info';
    const STYLE_WARNING = 'warning';
    const STYLE_DANGER = 'danger';
    const STYLE_CIRCLE = 'circle';
    const STYLE_INLINE = 'inline';

    public $checked = false;
    public $type = self::TYPE_CHECKBOX;
    public $style = self::STYLE_DEFAULT;
    public $list = [];
    public $wrapperOptions = [];

    public $groupOptions = [
        'checkbox' => false,
        'name' => 'aw',
        'options' => [],
    ];

    /**
     * @var GroupDto
     */
    private $_groupDto;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->_groupDto = new GroupDto($this->groupOptions);
        $checked = $this->hasModel() ? $this->model->{$this->attribute} : $this->checked;
        $this->_groupDto->setCheckedData($checked);
    }

    /**
     * @return string
     */
    public function run(): string
    {
        AwesomeCheckboxAsset::register($this->getView());
        //FontAwesomeAsset::register($this->getView());
        if (!empty($this->list) && \is_array($this->list)) {
            return $this->renderList();
        }

        return $this->renderItem();
    }

    /**
     * @return string
     */
    protected function renderItem(): string
    {
        $html = [];
        $html [] = Html::beginTag('div', array_merge(['class' => $this->getClass()], $this->wrapperOptions));
        $label = $this->getLabelContent();
        $html[] = $this->getInput();
        if ($label) {
            $html[] = Html::tag('label', $label, ['for' => $this->getLabelId()]);
        }
        $html [] = Html::endTag('div');

        return implode('', $html);
    }

    /**
     * @param $value
     * @return string
     */
    protected function renderGroupItem($value): string
    {
        if ($this->_groupDto->isCheckbox()) {
            $html[] = Html::beginTag('div', ['class' => 'row checkbox checkbox-info']);
            $html[] = Html::checkbox('rid[]', false, $this->_groupDto->getCheckboxOptions());
            $html[] = Html::tag('label', $value, $this->_groupDto->getLabelOptions());
            $html[] = Html::endTag('div');
        } else {
            $html[] = Html::tag('div', $value, $this->_groupDto->getOptions());
        }

        return implode(' ', $html);
    }

    /**
     * @return mixed
     */
    protected function renderList()
    {
        $listAction = $this->type . 'List';
        $this->options['item'] = function ($index, $label, $name, $checked, $value) {
            $action = $this->type;
            $id = strtolower($this->id . '-' . $index . '-' . str_replace(['[]', '][', '[', ']', ' ', '.'], ['', '-', '-', '', '-', '-'], $name));
            $html = [];

            if (\is_array($label)) {
                $html[] = $this->renderGroupItem($value);
                foreach ($label as $key => $item) {
                    if ($key && $item) {
                        $options = [
                            'id' => $id . $key,
                            'label' => null,
                            'value' => $key,
                            'data' => [
                                'rid' => $this->_groupDto->getValue(),
                            ],
                        ];
                        $html[] = Html::beginTag('div', ['class' => $this->getClass()]);
                        $html[] = Html::$action($name, $this->_groupDto->isCheckedItem($key), $options);
                        $html[] = Html::tag('label', $item, ['for' => $id . $key]);
                        $html[] = Html::endTag('div');
                    }
                }
                $this->_groupDto->next();
            } else {
                $options = [
                    'id' => $id,
                    'label' => null,
                    'value' => $value,
                ];
                $html[] = Html::beginTag('div', ['class' => $this->getClass()]);
                $html[] = Html::$action($name, $checked, $options);
                $html[] = Html::tag('label', $label, ['for' => $id]);
                $html[] = Html::endTag('div');
            }

            return implode(' ', $html);
        };

        if ($this->hasModel()) {
            $listAction = 'active' . ucfirst($listAction);
            $input = Html::$listAction($this->model, $this->attribute, $this->list, $this->options);
        } else {
            $input = Html::$listAction($this->name, $this->checked, $this->list, $this->options);
        }

        return $input;
    }

    /**
     * @return string
     */
    protected function getLabelContent(): string
    {
        $label = array_key_exists('label', $this->options) ? $this->options['label'] : '';
        if (empty($label) && $this->hasModel()) {
            $label = Html::encode($this->model->getAttributeLabel(Html::getAttributeName($this->attribute)));
        }
        $this->options['label'] = null;

        return $label;
    }

    /**
     * @return string
     */
    protected function getLabelId(): string
    {
        $id = $this->id;
        if (!array_key_exists('id', $this->options) && $this->hasModel()) {
            $id = Html::getInputId($this->model, $this->attribute);
        } elseif (isset($this->options['id'])) {
            $id = $this->options['id'];
        }

        return $id;
    }

    /**
     * @return string
     */
    protected function getInput(): string
    {
        $inputType = ucfirst($this->type);
        if ($this->hasModel()) {
            $inputType = 'active' . $inputType;
            $input = Html::$inputType($this->model, $this->attribute, $this->options);
        } else {
            $input = Html::$inputType($this->name, $this->checked, $this->options);
        }

        return $input;
    }

    /**
     * @return string
     */
    protected function getClass(): string
    {
        $class = [];
        $class[] = $this->type;
        if (!empty($this->style)) {
            if (\is_array($this->style)) {
                $class = array_merge($class, array_map(function ($item) {
                    return $this->type . '-' . $item;
                }, $this->style));
            } else {
                $class[] = $this->type . '-' . $this->style;
            }
        }
        if (isset($this->wrapperOptions['class']) && !empty($this->wrapperOptions['class'])) {
            $class = array_merge($class, preg_split('/\s+/', $this->wrapperOptions['class']));
        }

        return implode(' ', $class);
    }
}
