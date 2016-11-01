<?php

namespace lo\core\helpers;

/**
 * Class CalculationHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CalculationHelper
{
    /**
     * @param $value
     * @param $total
     * @return int
     */
    public static function persent($value, $total)
    {
        if ($total <= 0) return 0;
        if ($total < $value) return 100;

        return ($total - ($total - $value)) / $total * 100;
    }

}