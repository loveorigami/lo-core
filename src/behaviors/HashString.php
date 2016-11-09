<?php
namespace lo\core\behaviors;

/**
 * Class HashString
 * Поведение для генерации md5 хеша из строки
 * @package lo\core\behaviors
 * @property \lo\core\db\ActiveRecord $owner
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class HashString extends HashText
{
    /**
     * @var string|array
     */
    public $attribute = 'name';

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