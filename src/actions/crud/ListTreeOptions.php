<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 13.06.2018
 * Time: 19:24
 */

namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveRecord;
use lo\core\db\tree\TActiveRecord;
use lo\core\helpers\ArrayHelper;
use Yii;
use yii\bootstrap\Html;

/**
 * Class ListOptions
 * Класс действия создания элемента модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 * Use in controller as:
 *      'list' => [
 *          'class' => crud\ListOptions::class,
 *          'modelClass' => $class,
 *          'condition' => function ($query) {
 *              return $query->published();
 *          }
 *      ],
 */
class ListTreeOptions extends Base
{
    /** @var string сценарий */
    public $modelScenario = ActiveRecord::SCENARIO_SEARCH;

    /** @var int */
    public $depth = 1;

    public function run($q = null)
    {
        $obj = Yii::createObject(["class" => $this->modelClass, 'scenario' => $this->modelScenario]);
        $q = urldecode($q);

        /** @var TActiveRecord $model */
        $model = $obj::findByPk($q)->one();

        if (!$model) {
            // <option value="12838" selected="">Аренда автовышки</option>
            return Html::tag('options', 'No data', [
                'value' => 0,
                'selected' => 'selected'
            ]);
        }

        /** @var TActiveRecord[] $data */
        $data = $model->getDescendants($this->depth)->all();

        return Html::renderSelectOptions([], ArrayHelper::map($data, 'id', 'name'));
    }
}