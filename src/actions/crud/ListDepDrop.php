<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveRecord;
use lo\core\helpers\ArrayHelper;
use Yii;
use yii\web\Response;

/**
 * Class ListId
 * Класс действия создания элемента модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ListDepDrop extends Base
{
    /** @var string атрибут поиска по умолчанию */
    public $defaultAttr = 'name';

    /** @var string атрибут категории */
    public $idCatAttr = 'cat_id';

    public $optgroup;

    /** @var string сценарий */
    public $modelScenario = ActiveRecord::SCENARIO_SEARCH;

    public function run()
    {
        $obj = Yii::createObject(["class" => $this->modelClass, 'scenario' => $this->modelScenario]);
        $ids = Yii::$app->request->post('depdrop_parents');

        $out = ['output' => '', 'selected' => ''];
        if ($ids) {

            $cat_id = empty($ids[0]) ? null : $ids[0];
            $subcat_id = empty($ids[1]) ? null : $ids[1];

            if ($cat_id != null) {

                $data = ['out' => [], 'selected' => $subcat_id];

                $array = $obj::find()->where([$this->idCatAttr => $cat_id])->orderBy([
                    $this->idCatAttr => SORT_ASC
                ])->all();


                foreach ($array as $element) {
                    $key = ArrayHelper::getValue($element, 'id');
                    $value = ArrayHelper::getValue($element, 'name');

                    if ($this->optgroup !== null) {
                        $group = ArrayHelper::getValue($element, $this->optgroup);
                        $data['out'][$group][] =
                            [
                                'id' => $key,
                                'name' => $value
                            ];
                    } else {
                        $data['out'][] = [
                            'id' => $key,
                            'name' => $value
                        ];
                    }
                }

                /**
                 * the getProdList function will query the database based on the
                 * cat_id and sub_cat_id and return an array like below:
                 *  [
                 *      'out'=>[
                 *          ['id'=>'<prod-id-1>', 'name'=>'<prod-name1>'],
                 *          ['id'=>'<prod_id_2>', 'name'=>'<prod-name2>']
                 *       ],
                 *       'selected'=>'<prod-id-1>'
                 *  ]
                 */

                $out = ['output' => $data['out'], 'selected' => $data['selected']];

            }
        }

        // We know we can use ContentNegotiator filter
        // this way is easier to show you here :)
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $out;
    }
}