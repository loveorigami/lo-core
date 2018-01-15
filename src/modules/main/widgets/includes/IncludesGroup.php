<?php

namespace lo\core\modules\main\widgets\includes;

use lo\core\widgets\App;
use lo\modules\main\models\IncludeGroup;

/**
 * Class IncludesGroup
 * Виджет для отображения групп включаемых областей
 * @package lo\modules\main\widgets\includes
 */
class IncludesGroup extends App
{
    /** @var string символьный код группы областей */
    public $code;

    /** @var IncludeGroup группа областей */
    protected $group;

    /**
     * @return bool
     */
    public function init()
    {
        if (!$this->isShow()) {
            return null;
        }

        $this->locateGroup();
    }

    /**
     * Поиск подходящей включаемой области
     */
    protected function locateGroup()
    {
        $groups = IncludeGroup::find()->published()->andWhere(["code" => $this->code])->orderBy(['pos' => SORT_ASC])->all();
        foreach ($groups AS $group) {
            /** @var IncludeGroup $group */
            if ($group->isSuitable()) {
                $this->group = $group;
                break;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->isShow() OR empty($this->group)) {
            return false;
        }

        $includes = $this->group->includes;

        $html = [];

        foreach ($includes AS $include) {
            $html[] = IncludeItem::widget(["model" => $include]);
        }

        return implode("\r\n", $html);
    }

} 