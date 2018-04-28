<?php

namespace lo\core\helpers;

use lo\core\behaviors\upload\UploadedRemoteFile;

/**
 * Class UploadHelper
 * @package lo\core\helpers
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class UploadHelper
{
    /**
     * @param $url
     * @return UploadedRemoteFile
     */
    public static function fromUrl($url)
    {
        return UploadedRemoteFile::initWithUrl($url);
    }
}