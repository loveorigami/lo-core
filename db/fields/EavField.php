<?php

namespace lo\core\db\fields;

use yii\helpers\ArrayHelper;

/**
 * Class HtmlField
 * Поле WYSIWYG редактора. Использует CKEditor
 * @package lo\core\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class EavField extends Field
{
    public $showInGrid = false;
    public $showInFilter = false;
    public $isRequired = false;
    public $editInGrid = false;
    public $showInExtendedFilter = false;

    /**
     * @inheritdoc
     */

    public $inputClass = '\lo\core\inputs\EavInput';

    /**
     * Преффикс поведения
     */
    const BEHAVIOR_PREF = "eav";

    /**
     * @var array настройки поведения
     */

    public $eavOptions = [];
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        $parent = parent::behaviors();

            $code = self::BEHAVIOR_PREF . ucfirst($this->attr);
            $parent[$code] = ArrayHelper::merge([
                'class' => \lo\modules\eav\EavBehavior::className(),
                'valueClass' => \lo\modules\eav\models\EavAttributeValue::className(),
            ], $this->eavOptions);

        return $parent;
    }

    protected function view()
    {
        $view = parent::view();
        $view["format"] = 'html';
        $view["value"] = $this->getStringValue($this->model);
        return $view;
    }

    protected function getStringValue($model)
    {
        $arr = [];
        foreach ($model->getEavAttributes()->all() AS $attr) {
            $arr[] = $model[$attr->name];
        }

        return implode("<br>", $arr);

    }

}