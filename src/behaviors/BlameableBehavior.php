<?php

namespace lo\core\behaviors;

use Yii;
use yii\behaviors\BlameableBehavior as YiiBlameableBehavior;

/**
 * Class BlameableBehavior
 */
class BlameableBehavior extends YiiBlameableBehavior
{
    public $defaultUserId = 1;

    /**
     * @inheritdoc
     *
     * In case, when the [[value]] property is `null`, the value of `Yii::$app->user->id` will be used as the value.
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            $user = Yii::$app->get('user', false);
            return $user && !$user->isGuest ? $user->id : $this->defaultUserId;
        }
        return parent::getValue($event);
    }

}
