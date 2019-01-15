<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 20.08.2016
 * Time: 16:54
 */

namespace lo\core\behaviors\upload;

/**
 * Class IUploadFile
 * Базовый класс полей.
 *
 * @package lo\interfaces
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
interface IUploadImage extends IUploadFile
{
    /**
     * @param string  $attribute
     * @param string  $profile
     * @param boolean $old
     * @return string
     */
    public function getThumbUploadPath($attribute, $profile = 'thumb', $old = false): ?string;


    /**
     * @param string $attribute
     * @param string $profile
     * @return string|null
     */
    public function getThumbUploadUrl($attribute, $profile = 'thumb'): ?string;

}
