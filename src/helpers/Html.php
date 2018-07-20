<?php

namespace lo\core\helpers;

use yii\helpers\Html as BaseHtml;

/**
 * Class Html
 *
 * @package lo\core\helpers
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class Html extends BaseHtml
{
    public static function tel($text, $phone = null, array $options = []): string
    {
        $phone = $phone ?? $text;
        $phone = mb_convert_kana($phone, 'a', 'UTF-8'); // normalization
        $phone = preg_replace('~[^+0-9]+~', '', $phone); // normalization

        $options['href'] = 'tel:' . $phone;

        return static::tag('a', $text, $options);
    }

}
