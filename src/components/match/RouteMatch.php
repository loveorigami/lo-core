<?php

namespace lo\core\components\match;

use lo\core\helpers\StringHelper;
use Yii;

/**
 * Class RouteMatch
 * Совпадение с текущим маршрутом
 *
 * @package lo\core\components
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class RouteMatch extends Match
{
    /**
     * Проверяет истинность php выражения
     *
     * @param string $value строка с php кодом. Например return 1==1;
     * @return boolean
     */
    public function test($value): bool
    {
        /** удаляем пробелы */
        $value = StringHelper::clearSpace($value);
        $data = explode(',', $value);

        return \in_array(Yii::$app->requestedRoute, $data, false);
    }
}
