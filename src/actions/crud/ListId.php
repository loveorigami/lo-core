<?php

namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;
use Yii;
use yii\web\Response;

/**
 * Class ListId
 * Класс действия создания элемента модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 * Use in controller as:
 *      'list' => [
 *          'class' => crud\ListId::class,
 *          'modelClass' => $class,
 *          'condition' => function ($query) {
 *              return $query->published();
 *          }
 *      ],
 */
class ListId extends Base
{
    /** @var array атрибут поиска по умолчанию */
    public $defaultAttr = 'name';

    /** @var string сценарий */
    public $modelScenario = ActiveRecord::SCENARIO_SEARCH;

    /** @var ActiveQuery */
    public $condition;

    /** @var int */
    public $limit = 10;

    public function run($q = null)
    {
        $obj = Yii::createObject(["class" => $this->modelClass, 'scenario' => $this->modelScenario]);
        $q = urldecode($q);

        $out['results'][] = ['id' => 1, 'text' => ''];

        if (!is_null($q)) {
            /** @var ActiveQuery $query */
            $query = $obj::find()
                ->select('id,' . $this->defaultAttr)
                ->where(['like', $this->defaultAttr, $q]);

            if ($this->condition instanceof \Closure) {
                call_user_func($this->condition, $query);
            };

            $data = $query->limit($this->limit)->all();

            foreach ($data as $m) {
                $out['results'][] = [
                    'id' => (int)$m->id,
                    'text' => $m->{$this->defaultAttr}
                ];
            }
        }

        // We know we can use ContentNegotiator filter
        // this way is easier to show you here :)
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $out;
    }
}
