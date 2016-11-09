<?php
namespace lo\core\behaviors;

/**
 * Class HashEmail
 * Поведение для генерации md5 хеша
 * @package lo\core\behaviors
 * @property \lo\core\db\ActiveRecord $owner
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class HashEmail extends HashText
{
    /**
     * @var string|array
     */
    public $attribute = 'email';

    /**
     * generate md5 hash
     * @return string
     */
    protected function getHash()
    {
        $owner = $this->owner;
        $str = $owner->{$this->attribute};

        $str = md5(trim($str));

        return $str;
    }
}