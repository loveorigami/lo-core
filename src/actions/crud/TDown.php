<?php
namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\tree\TActiveRecord;
use lo\core\helpers\PkHelper;
use Yii;

/**
 * Class TDown
 * Класс действия для перемещения вниз древовидной модели
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class TDown extends Base
{
    /**
     * @inheritdoc
     */
    public function run($id)
    {
        $pk = PkHelper::decode($id);

        /** @var TActiveRecord $model */
        $model = $this->findModel($pk);

        $this->canAction($model);

        $nextModel = $model->getNext()->one();

        if ($nextModel){
            $model->insertAfter($nextModel)->save();
        }

        if (!Yii::$app->request->isAjax)
            return $this->goBack();

        return null;
    }
}