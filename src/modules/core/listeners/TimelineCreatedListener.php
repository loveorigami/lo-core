<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 23.11.2017
 * Time: 12:32
 */

namespace lo\core\modules\core\listeners;

use lo\core\interfaces\ITimelineEvent;
use lo\core\modules\core\models\TimelineEvent;
use Yii;

class TimelineCreatedListener
{
    /**
     * @param $event
     * @return bool
     */
    public function handle(ITimelineEvent $event)
    {
        $model = new TimelineEvent();
        $model->application = Yii::$app->id;
        $model->category = $event->getCategory();
        $model->event = $event->getEvent();
        $model->data = json_encode($event->getData(), JSON_UNESCAPED_UNICODE);
        return $model->save(false);
    }
}