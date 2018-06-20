<?php

namespace lo\core\actions\crud;

use lo\core\db\ActiveQuery;
use Yii;
use yii\helpers\ArrayHelper;
use lo\core\db\ActiveRecord;
use lo\core\actions\Base;

/**
 * Class Index
 * Класс действия для вывода списка моделей для администрирования
 *
 * @package lo\core\actions
 */
class Index extends Base
{
    /** @var string сценарий для валидации */
    public $modelScenario = ActiveRecord::SCENARIO_SEARCH;

    /** @var array настройки провайдера данных */
    public $dataProviderConfig = [];

    /** @var string путь к шаблону для отображения */
    public $tpl = "index";

    /** @var int количество элементов на странице */
    public $pageSize = 20;

    /** @var array сортировка */
    public $orderBy;

    /** @var array значения атрибутов используемые по умолчанию в фильтре моделей */
    public $defaultSearchAttrs;

    /** @var ActiveQuery */
    public $condition;

    /**
     * Запуск действия вывода списка моделей
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        /** @var ActiveRecord $searchModel */
        $searchModel = new $this->modelClass;

        /* if (!Yii::$app->user->can('listModels', array("model" => $searchModel)))
          throw new ForbiddenHttpException('Forbidden'); */

        $searchModel->setScenario($this->modelScenario);
        $requestParams = Yii::$app->request->getQueryParams();

        if (!empty($this->defaultSearchAttrs)) {
            $requestParams = ArrayHelper::merge(
                [$searchModel->formName() => $this->defaultSearchAttrs],
                $requestParams
            );
        }

        $dataProvider = $searchModel->search($requestParams, $this->dataProviderConfig);

        /** @var ActiveQuery $query */
        $query = $dataProvider->query;

        if ($this->condition instanceof \Closure) {
            call_user_func($this->condition, $query);
        };

        $perm = $searchModel->getPermission();

        if ($perm) {
            $perm->applyConstraint($query);
        }

        $pageSizeParam = $dataProvider->pagination->pageSizeParam;
        $pageSize = isset($requestParams[$pageSizeParam]) ? intval($requestParams[$pageSizeParam]) : $this->pageSize;

        $dataProvider->pagination->pageSize = $pageSize;

        if ($this->orderBy) {
            $dataProvider->sort->defaultOrder = $this->orderBy;
        }

        $params = [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ];

        if (!Yii::$app->request->isAjax) {
            return $this->render($this->tpl, $params);
        } else {
            return $this->renderPartial($this->tpl, $params);
        }
    }
}
