<?php

namespace lo\core\actions\crud;

use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;
use lo\core\db\tree\TActiveRecord;
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

    /**
     * @param int $parent_id
     * @return string
     */
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

        /** @var TActiveRecord $parentModel */
        if ($parentModel) {
            $query = $parentModel->getDescendants();
        } else {
            $query = $searchModel::find();
        }

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search($requestParams, $this->dataProviderConfig, $query);
        $perm = $searchModel->getPermission();

        if ($perm) {
            /** @var ActiveQuery $dataQuery */
            $dataQuery = $dataProvider->query;
            $perm->applyConstraint($dataQuery);
        }

        $dataProvider->getPagination()->pageSize = $this->pageSize;

        if ($this->orderBy) {
            $dataProvider->getSort()->defaultOrder = $this->orderBy;
        }

        $params = [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'parent_id' => $parent_id,
        ];

        if (!Yii::$app->request->isAjax) {
            return $this->render($this->tpl, $params);
        } else {
            return $this->renderPartial($this->tpl, $params);
        }
    }
}