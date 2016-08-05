<?php

namespace lo\core\db\fields;

use yii\helpers\ArrayHelper;

use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;

use lo\modules\eav\EavQueryTrait;

/**
 * Class HtmlField
 * Поле WYSIWYG редактора. Использует CKEditor
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class EavField extends BaseField
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

    public function behaviors()
    {
        $parent = parent::behaviors();

            $code = self::BEHAVIOR_PREF . ucfirst($this->attr);

            $parent[$code] = ArrayHelper::merge([
                'class' => \lo\modules\eav\EavBehavior::className(),
            ], $this->eavOptions);


        return $parent;

    }

    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();
        $grid["value"] = 111;

        return $grid;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [$this->attr, 'safe'];
        return $rules;
    }

    /**
     * Поиск
     * @param ActiveQuery $query запрос
     */
    protected function search(ActiveQuery $query)
    {
       parent::search($query);
        $query->andEavFilterWhere('=', 'eav_name', Yii::$app->getRequest()->get('eav_name'));
        //return true;
    }

}