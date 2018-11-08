<?php

namespace lo\core\components\match;

/**
 * Class PhpMatch
 * Php выражение
 *
 * @package lo\core\components
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class PhpMatch extends Match
{
    /**
     * Проверяет истинность php выражения
     *
     * @param string $value строка с php кодом. Например return 1==1;
     * @return boolean
     */
    public function test($value): bool
    {
        return eval($value);
    }
}
