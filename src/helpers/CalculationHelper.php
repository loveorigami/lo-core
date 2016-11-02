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
    public static function percent($value, $total)
    {
        if ($total <= 0) return 0;
        if ($total < $value) return 100;

        return round(($total - ($total - $value)) / $total * 100, 2);
    }

}