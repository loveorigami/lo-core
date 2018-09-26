<?php

namespace lo\core\db\fields;

use lo\core\db\ActiveRecord;
use lo\core\helpers\Memorize;
use lo\modules\eavb\behaviors\EavEntity;
use lo\modules\eavb\models\Entity;
use lo\modules\eavb\models\Set;
use lo\modules\eavb\models\Value;
use yii\helpers\ArrayHelper;

use lo\core\db\ActiveQuery;
use yii\validators\Validator;

/**
 * Class HtmlField
 * Поле WYSIWYG редактора. Использует CKEditor
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 *
 * @property array $eavGridColumns
 */
class EavField extends BaseField
{
    public $showInGrid = true;
    public $showInFilter = true;
    public $isRequired = false;
    public $editInGrid = false;
    public $showInExtendedFilter = false;

    public $eavAttributes = [];

    /** @var string */
    public $inputClass = 'lo\core\inputs\EavInput';

    /** Преффикс поведения*/
    const BEHAVIOR_PREF = "eav";

    /** @var array настройки поведения */
    public $eavOptions = [];
    public $eavCondition;

    /**
     * @return array
     */
    public function behaviors()
    {
        $parent = parent::behaviors();
        $code = static::BEHAVIOR_PREF . $this->attr;

        $parent[$code] = ArrayHelper::merge([
            'class' => EavEntity::class,
            'entity' => function () {
                return new Entity([
                    'sets' => Memorize::call(
                        [Set::class, 'findAll'], [['slug' => $this->eavCondition]]
                    ),
                ]);
            },
        ], $this->eavOptions);

        $validators = $this->model->getValidators();
        $validators->append(Validator::createValidator('safe', $this->model, $this->eavAttributes));

        return $parent;
    }

    /**
     * @return array|bool
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [$this->eavAttributes, 'safe'];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = $this->defaultGrid();
        $cols = $this->getEavGridColumns();

        if ($cols) {
            $grid['columns'] = $cols;
        }
        return $grid;
    }


    /**
     * Поиск
     * @param ActiveQuery $query запрос
     */
    protected function search(ActiveQuery $query)
    {
        //d($this->model->eav->getAttributes()); exit;
        //d($this->model->eav); exit;

        foreach ($this->model->getEav()->getAttributes() as $key => $value) {
            /** @var Value $valueModel */
            $attrModel = $this->model->getEav()->getAttributeModel($key);
            $valueModel = $attrModel->createValue();
            $valueModel->setScenario('search');
            $valueModel->load(['value' => $value], '');
            //d($valueModel); exit;
            if ($valueModel->validate(['value'])) {
                $valueModel->addJoin($query, $this->model->tableName());
                $valueModel->addWhere($query);
            }
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getEavGridColumns()
    {
        $result = [];

        /** @var Entity $entity */
        $entity = $this->model->getEav();

        foreach ($entity->getAttributesConfig() as $code => $item) {
            if (in_array($code, $this->eavAttributes)) {
                $column = [
                    'label' => $item['name'],
                    'attribute' => $code,
                ];
                //$column = self::addFilter($column, $item, $searchModel);
                // $column = self::addValue($column, $item);
                $result[] = $column;
            }
        }

        return $result;
    }
}
