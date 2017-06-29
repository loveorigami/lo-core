<?php
namespace lo\core\modules\core\handlers;

use lo\core\modules\core\models\Template;
use Yii;

/**
 * Class LayoutHandler
 * @package lo\modules\core\handlers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class LayoutHandler
{
    /**
     * setLayout handler
     */
    const HANDLER_SET_LAYOUT = 'setLayout';

    /**
     * set layout
     */
    public static function setLayout()
    {
        list ($route) = Yii::$app->getRequest()->resolve();
        if ($route === 'debug/default/view') return;

        if (!Yii::$app->request->isAjax) {
            /** @var Template []$models */
            $models = Template::find()->published()->orderBy(['pos' => SORT_ASC])->all();
            foreach ($models as $model) {
                if ($model->isSuitable()) {
                    Yii::$app->controller->layout = $model->layout;
                    return;
                }
            }
        }
    }
}