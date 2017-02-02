<?php
namespace lo\core\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Filter deny or allow access to actions of controllers.
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => AjaxFilter::className(),
 *             'actions' => ['actionName', 'actionName2'],
 *         ],
 *     ];
 * }
 *```
 * @version 0.1.0
 */
class AjaxFilter extends Behavior
{
    /**
     * @var array Actions of controller which will be apply this filter.
     */
    public $actions = [];

    /**
     * @return array
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param $event
     * @throws BadRequestHttpException
     */
    public function beforeAction($event)
    {
        if (in_array($event->action->id, $this->actions)) {
            if (!Yii::$app->request->isAjax) {
                throw new BadRequestHttpException('This url can only be accesed by ajax');
            }
        }
    }
}