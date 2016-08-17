<?php
namespace lo\core\modules\permission\models;

use lo\core\db\ActiveQuery;
use lo\core\db\ActiveRecord;
use lo\core\interfaces\IConstraint;
use lo\core\interfaces\IPermission;
use Yii;

/**
 * Class Permission
 * Модель прав доступа
 * @package lo\core\modules\permission\models
 */
class Constraint extends ActiveRecord implements IPermission
{
    /** @var Constraint[] */
    protected static $_permissions = [];

    /** @var IConstraint объект ограничения доступа */
    protected $_constraintObject;

    /** @var array массив атрибутов запрещенных к редактированию */
    protected $_forbiddenAttrs;

    /**
     * @return string
     */
    public static function tableName()
    {
        return "{{%auth_constraint}}";
    }

    /**
     * @return mixed
     */
    public function metaClass()
    {
        return ConstraintMeta::class;
    }

    /**
     * Возвращает объект прав доступа для модели с заданным классом
     * @param string $class класс модели
     * @param string $role роль пользователя по умолчанию
     * @return Constraint
     */
    public static function findPermission($class, $role)
    {
        if (substr($class, 0, 1) != '\\') {
            $class = '\\' . $class;
        }

        if (!isset(self::$_permissions[$class])) {
            self::$_permissions[$class] = static::find()->where([
                "model" => $class,
                "role" => $role
            ])->published()->one();
        }

        return self::$_permissions[$class];
    }

    /**
     * Возвращает объект ограничения доступа
     * @return IConstraint
     */
    public function getConstraintObject()
    {
        if ($this->_constraintObject === null AND !empty($this->constraint)) {
            $this->_constraintObject = Yii::createObject($this->constraint);
        }
        return $this->_constraintObject;
    }

    /**
     * Применяет ограничение к запрос
     * @param ActiveQuery $query запрос
     */
    public function applyConstraint($query)
    {
        $constraint = $this->getConstraintObject();
        if ($constraint){
            $constraint->applyConstraint($query);
        }
    }

    /**
     * Является ди атрибут запрещенным к редактированию
     * @param string $attr атрибут
     * @return bool
     */
    public function isAttributeForbidden($attr)
    {
        $arr = $this->getForbiddenAttrs();
        return in_array($attr, $arr);
    }

    /**
     * Возвращает массив имен атрибутов запрещенных к редактировнаию
     * @return array
     */
    public function getForbiddenAttrs()
    {
        if ($this->_forbiddenAttrs === null) {

            $arr = [];
            $strs = explode("\n", $this->getForbiddenAttrs());

            foreach ($strs AS $str) {
                $str = trim($str);

                if (!empty($str)){
                    $arr[] = $str;
                }
            }

            $this->_forbiddenAttrs = $arr;
        }

        return $this->_forbiddenAttrs;
    }

    /**
     * Присутствуют ли в массиве атрибутов запрещенные к изменению
     * @param array $attrs массив атрибутов key=>value
     * @return bool
     */
    public function hasForbiddenAttrs($attrs)
    {
        $arr = $this->getForbiddenAttrs();
        $keys = array_keys($attrs);
        $inter = array_intersect($keys, $arr);
        return count($inter) > 0;
    }
}