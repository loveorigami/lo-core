<?php

namespace lo\core\components\match;

use Yii;

/**
 * Class UrlMatch
 * Совпадение по URL
 *
 * @package lo\core\components
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class UrlMatch extends Match
{
    /**
     * Проверяет соответствие текущего url заданному значению
     *
     * @param string $value строка с шаблонами url разделенным запятыми
     * @return boolean
     */
    public function test($value): bool
    {
        $condArr = explode(',', $value);
        $url = '/' . Yii::$app->request->pathInfo;

        foreach ($condArr AS $cond) {
            $cond = trim($cond);
            $pattern = '!^' . str_replace('*', '.*?', $cond) . '$!i';

            /** Совпадение url одному из шаблонов */
            if (preg_match($pattern, $url)) {
                return true;
            }
        }

        return false;
    }
}
