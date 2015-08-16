<?php
namespace lo\core\components;

use Yii;

/**
 * Class UrlMatch
 * Совпадение по URL
 * @package lo\core\components
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class UrlMatch extends Match
{

    /**
     * Проверяет соответствие текущего url заданному значению
     * @param string $value строка с шаблонами url разделенным запятыми
     * @return boolean
     */
    public function test($value)
    {

        $condArr = explode(",", $value);

        $url = "/" . Yii::$app->request->pathInfo;

        foreach ($condArr AS $cond) {

            $cond = trim($cond);

            $pattern = "!^" . str_replace("*", ".*?", $cond) . "$!i"; // Преобразуем шаблон в регулярное выражение

            if (preg_match($pattern, $url)) // Совпадение url одному из шаблонов
            {
                return true;
            }
        }

        return false;

    }

}
