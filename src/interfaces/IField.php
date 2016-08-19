<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 19.08.2016
 * Time: 11:04
 */
namespace lo\core\interfaces;

use lo\core\db\ActiveQuery;
use Yii\widgets\ActiveForm;


/**
 * Class IField
 * Базовый класс полей.
 * @package lo\interfaces
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
interface IField
{
    /**
     * Формирование Html кода поля для вывода в расширенном фильтре
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @return string
     */
    public function getExtendedFilterForm(ActiveForm $form, Array $options = []);

    /**
     * Формирование Html кода поля для вывода в форме
     * @param ActiveForm $form объект форма
     * @param array $options массив html атрибутов поля
     * @param bool|int $index инднкс модели при табличном вводе
     * @param string|array $cls класс поля, либо конфигурационный массив
     * @return string
     */
    public function getForm(ActiveForm $form, Array $options = [], $index = false, $cls = null);

    /**
     * Возвращает подпись атрибута
     * @return array
     */
    public function getAttributeLabel();

    /**
     * Результурующая конфигурация поля грида (GridView)
     * @return array
     */
    public function getGrid();

    /**
     * Возвращает значение фильтра для грида
     * @return mixed
     */
    public function getGridFilter();

    /**
     * @param $value mixed установка значения фильтра
     */
    public function setGridFilter($value);

    /**
     * Результирующая конфигурация поля для детального просмотра
     * @return array
     */
    public function getView();

    /**
     * Правила валидации
     * @return array|bool
     */
    public function rules();

    /**
     * Поведения
     * @return array
     */
    public function behaviors();

    /**
     * Возвращает массив данных ассоциированных с полем
     * @return array
     */
    public function getDataValue();

    /**
     * Накладывает ограничение на поиск
     * @param ActiveQuery $query
     */
    public function applySearch(ActiveQuery $query);

}