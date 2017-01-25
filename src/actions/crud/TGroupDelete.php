<?php
namespace lo\core\actions\crud;

use lo\core\db\TActiveRecord;
use lo\core\helpers\PkHelper;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * Class TGroupDelete
 * Класс для группового удаления древовидных моделей
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TGroupDelete extends GroupDelete
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var TActiveRecord $class */
        $class = $this->modelClass;
        $ids = Yii::$app->request->post($this->groupIdsAttr, array());
        $ids = PkHelper::decodeAll($ids);

        if (!empty($ids)) {
            foreach ($ids AS $id) {
                $model = $class::findOne($id);
                if (!$model)
                    continue;
                if (!Yii::$app->user->can($this->access(), array("model" => $model)))
                    throw new ForbiddenHttpException('Forbidden');
                $model->deleteWithChildren();
            }
        }

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

        return null;
    }
}