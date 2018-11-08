<?php

namespace lo\core\behaviors;

use lo\core\components\match\Match;
use yii\base\Behavior;
use yii\base\InvalidConfigException;

/**
 * Class MatchSuitable
 * Поведение для определения того, что выполняется заданное у модели условие
 *
 * @package lo\core\behaviors
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class MatchSuitable extends Behavior
{
    /** @var string имя атрибута содержащего условие */
    public $condAttr = 'cond';

    /** @var string имя атрибута содержащего тип условия */
    public $condTypeAttr = 'cond_type';

    /**
     * Выполняется ли условие
     *
     * @return bool
     * @throws InvalidConfigException
     */
    public function isSuitable(): bool
    {
        if (empty($this->owner->{$this->condAttr})) {
            return true;
        }

        $match = Match::getMatch($this->owner->{$this->condTypeAttr});
        if ($match) {
            return $match->test($this->owner->{$this->condAttr});
        }

        return false;
    }
}
