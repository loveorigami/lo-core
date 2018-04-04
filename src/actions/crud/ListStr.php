<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveRecord;
use Yii;
use yii\web\Response;

/**
 * Class Create
 * Класс действия создания элемента модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ListStr extends Base
{
    /** @var array атрибут поиска по умолчанию */
    public $defaultAttr = 'name';

    /** @var string сценарий */
    public $modelScenario = ActiveRecord::SCENARIO_SEARCH;

    /**
     * @param $query
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function run($query)
    {
        $obj = Yii::createObject(["class" => $this->modelClass, 'scenario' => $this->modelScenario]);

        $query = urldecode($query);
        $models = $obj::find()->where(['like', $this->defaultAttr, $query])->all();

        $items = [];
        foreach ($models as $model) {
            $items[] = ['name' => $model->{$this->defaultAttr}];
        }

        // We know we can use ContentNegotiator filter
        // this way is easier to show you here :)
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $items;
    }

}