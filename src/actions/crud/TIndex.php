<?php

namespace lo\core\actions\crud;

use lo\core\db\ActiveRecord;
use lo\core\db\tree\TreeInterface;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class TIndex
 * Класс действия для вывода списка древовидных моделей для администрирования
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TIndex extends Index
{
    /**
     * @var array
     */
    public $orderBy = ['id' => SORT_DESC];

    /**
     * @var string имя параметра передаваемого расширенным фильтром
     */
    public $extFilterParam = "extendedFilter";

    public function run($parent_id = 0)
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;
        /** @var TreeInterface|ActiveRecord $searchModel */
        $searchModel = new $class;
        if (!$parent_id) $parent_id = $searchModel->getRootId();

        $searchModel->setScenario($this->modelScenario);
        $requestParams = Yii::$app->request->getQueryParams();

        $parentModel = $class::findOne($parent_id);

        if ($parentModel) {
            $query = $parentModel->getDescendants();
        } else {
            $query = $searchModel::find();
        }

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search($requestParams, $this->dataProviderConfig, $query);
        $perm = $searchModel->getPermission();

        if ($perm){
            $perm->applyConstraint($dataProvider->query);
        }

        $dataProvider->getPagination()->pageSize = $this->pageSize;

        if ($this->orderBy){
            $dataProvider->getSort()->defaultOrder = $this->orderBy;
        }

        $params = [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'parent_id' => $parent_id,
        ];

        if (!Yii::$app->request->isAjax)
            return $this->render($this->tpl, $params);
        else
            return $this->renderPartial($this->tpl, $params);

    }

}