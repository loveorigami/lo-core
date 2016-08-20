<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 20.08.2016
 * Time: 16:54
 */
namespace lo\core\interfaces;

/**
 * Class IUploadFile
 * Базовый класс полей.
 * @package lo\interfaces
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
interface IUploadFile
{
    /**
     * Returns file path for the attribute.
     * @param string $attribute
     * @param boolean $old
     * @return string|null the file path.
     */
    public function getUploadPath($attribute, $old = false);

    /**
     * Returns file url for the attribute.
     * @param string $attribute
     * @return string|null
     */
    public function getUploadUrl($attribute);

    /**
     * This method is called at the end of inserting or updating a record.
     */
    public function afterSave();

    /**
     * This method is invoked after deleting a record.
     */
    public function afterDelete();

}