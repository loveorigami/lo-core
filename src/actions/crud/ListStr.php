<?php
namespace lo\core\actions\crud;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class Create
 * Класс действия создания элемента модели
 * @package lo\core\actions\crud
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class ListStr extends \lo\core\actions\Base
{
    /**
     * @var array атрибут поиска по умолчанию
     */

    public $defaultAttr =  'name';

    public $modelScenario = 'search';

    public function run($query)
    {
        $obj = Yii::createObject(["class" => $this->modelClass, 'scenario' => $this->modelScenario]);

        $query=urldecode($query);
        $models = $obj::find()->where(['like', 'name', $query])->all();

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