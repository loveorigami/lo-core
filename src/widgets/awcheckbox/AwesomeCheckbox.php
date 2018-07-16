<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 13.07.2016
 * Time: 8:40
 */

namespace lo\core\widgets\awcheckbox;

use lo\core\helpers\ArrayHelper;
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

    public $groupCheckbox = false;
    public $groupName = 'aw';

    protected $groupId = 1;

    public function run()
    {
        AwesomeCheckboxAsset::register($this->getView());
        //FontAwesomeAsset::register($this->getView());
        if (!empty($this->list) && is_array($this->list)) {
            return $this->renderList();
        } else {
            return $this->renderItem();
        }
    }

    /**
     * @return string
     */
    protected function renderItem()
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
     *
     */
    protected function renderList()
    {
        $listAction = $this->type . 'List';
        $this->options['item'] = function ($index, $label, $name, $checked, $value) {
            $action = $this->type;
            $id = strtolower($this->id . '-' . $index . '-' . str_replace(['[]', '][', '[', ']', ' ', '.'], ['', '-', '-', '', '-', '-'], $name));
            $html = [];

            if (is_array($label)) {
                if ($this->groupCheckbox) {
                    $html[] = Html::beginTag('div', ['class' => 'row checkbox checkbox-info']);
                    $html[] = Html::checkbox('rid[]', false, [
                        'id' => $this->groupName . $this->groupId,
                        'class' => 'row row-group',
                        'value' => $this->groupName . $this->groupId,
                    ]);
                    $html[] = Html::tag('label', $value, ['for' => $this->groupName . $this->groupId]);
                    $html[] = Html::endTag('div');
                } else {
                    $html[] = Html::tag('div', $value, ['class' => 'label-default', 'style' => 'padding:0 5px;']);
                }
                $check = $this->hasModel() ? $this->model->{$this->attribute} : (array)$this->checked;
                foreach ($label as $key => $item) {
                    if ($key && $item) {
                        $options = [
                            'label' => null,
                            'value' => $key,
                            'id' => $this->getLabelId().$key,
                            'data' => [
                                'rid' => $this->groupName . $this->groupId,
                            ],
                        ];
                        $html[] = Html::beginTag('div', ['class' => $this->getClass()]);
                        $html[] = Html::$action($name, in_array($key, $check), $options);
                        $html[] = Html::tag('label', $item, ['for' => $this->getLabelId().$key]);
                        $html[] = Html::endTag('div');
                    }
                }
                $this->groupId++;
            } else {
                $options = [
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
    protected function getLabelContent()
    {
        $label = array_key_exists('label', $this->options) ? $this->options['label'] : '';
        if ($this->hasModel() && empty($label)) {
            $label = Html::encode($this->model->getAttributeLabel(Html::getAttributeName($this->attribute)));
        }
        $this->options['label'] = null;

        return $label;
    }

    /**
     * @return string
     */
    protected function getLabelId()
    {
        $id = $this->id;
        if ($this->hasModel() && !array_key_exists('id', $this->options)) {
            $id = Html::getInputId($this->model, $this->attribute);
        } elseif (isset($this->options['id'])) {
            $id = $this->options['id'];
        }

        return $id;
    }

    /**
     * @return string
     */
    protected function getInput()
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
    protected function getClass()
    {
        $class = [];
        $class[] = $this->type;
        if (!empty($this->style)) {
            if (is_array($this->style)) {
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
