<?php
namespace lo\core\helpers;

/**
 * Class PurifierHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class PurifierHelper
{
    /**
     * @return array
     */
    public static function text()
    {
        return [
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => true,
            'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
            'AutoFormat.Linkify' => true,
            'Core.EscapeInvalidTags' => false,
            'HTML.Allowed' => 'p,ul,li,b,i,a[href],pre',
            'HTML.Nofollow' => true,
        ];
    }
}