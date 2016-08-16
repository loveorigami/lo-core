<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveRecord;
use Yii;
use yii\web\Response;

/**
 * Class ListId
 * Класс действия создания элемента модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ListId extends Base
{
    /** @var array атрибут поиска по умолчанию */
    public $defaultAttr = 'name';

    /** @var string сценарий */
    public $modelScenario = ActiveRecord::SCENARIO_SEARCH;

    public function run($q = null)
    {
        $obj = Yii::createObject(["class" => $this->modelClass, 'scenario' => $this->modelScenario]);
        $q = urldecode($q);

        $out['results'][] = ['id' => 1, 'text' => ''];

        if (!is_null($q)) {
            $query = $obj::find()->select('id, name')->where(['like', 'name', $q])->limit(10)->all();
            foreach ($query as $m) {
                $out['results'][] = [
                    'id' => (int)$m->id,
                    'text' => $m->name
                ];
            }
        }

        // We know we can use ContentNegotiator filter
        // this way is easier to show you here :)
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $out;
    }
}