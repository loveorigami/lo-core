<?php
namespace lo\core\rbac;

/**
 * Interface IPermission
 * Интерфейс проверки прав доступа
 * @package lo\core\rbac
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */

interface IPermission
{

    public function applyConstraint($query);

    /**
     * Возвращает массив имен атрибутов запрещенных к редактировнаию
     * @return array
     */

    public function getForbiddenAttrs();

    /**
     * Является ди атрибут запрещенным к редактированию
     * @param string $attr атрибут
     * @return bool
     */

    public function isAttributeForbidden($attr);

    /**
     * Присутствуют ли в массиве атрибутов запрещенные к изменению
     * @param array $attrs массив атрибутов key=>value
     * @return bool
     */

    public function hasForbiddenAttrs($attrs);

}