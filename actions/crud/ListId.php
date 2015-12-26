<?php
namespace lo\core\actions\crud;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class Create
 * Класс действия создания элемента модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ListId extends \lo\core\actions\Base
{
    /**
     * @var array атрибут поиска по умолчанию
     */

    public $defaultAttr =  'name';

    public $modelScenario = 'search';

    public function run($q = null, $id = null)
    {
        $obj = Yii::createObject(["class" => $this->modelClass, 'scenario' => $this->modelScenario]);
        $q=urldecode($q);

        $out = ['results' => ['id' => '', 'text' => '']];

        if (!is_null($q)) {
            $data = $obj::find()->select('id, name')->where(['like', 'name', $q])->limit(10)->all();
            $out['results'] = array_values($data);
            foreach ($data as $model) {
                $out['results'][] = ['id'=>$model->id, 'text' => $model->{$this->defaultAttr}];
            }
        }
/*        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => City::find($id)->name];
        }*/

        // We know we can use ContentNegotiator filter
        // this way is easier to show you here :)
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $out;


    }

}