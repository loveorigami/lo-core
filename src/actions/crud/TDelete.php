<?php
namespace lo\core\actions\crud;

use lo\core\db\tree\TActiveRecord;
use lo\core\helpers\PkHelper;
use Yii;

/**
 * Class TDelete
 * Класс действия для удаления древовидной модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TDelete extends Delete
{
    /**
     * @inheritdoc
     */
    public function run($id)
    {
        if (Yii::$app->request->isPost) {
            $pk = PkHelper::decode($id);
            /** @var TActiveRecord $model */
            $model = $this->findModel($pk);
            $this->canAction($model);
            $model->deleteWithChildren();
        }

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

        return null;
    }

}