<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 19.08.2016
 * Time: 11:04
 */
namespace lo\core\interfaces;

/**
 * Class IUser
 * Базовый класс пользователя.
 * @package lo\interfaces
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
interface IUser
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getEmail();
}