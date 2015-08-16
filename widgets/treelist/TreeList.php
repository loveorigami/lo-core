<?php
namespace lo\core\widgets\treelist;

use Yii;

/**
 * Class TreeList
 * Виджет для вывода дерева. Отображаемая сущность должна наследовать \common\db\TActiveRecord
 * @package app\modules\main\widgets\treelist
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class TreeList
{

    /**
     * @var string имя класса модели
     */
    public $modelClass;

    /**
     * @var int идентификатор родительского раздела
     */
    public $parentId;

    /**
     * @var string класс для активного раздела
     */

    public $actClass = "active";

    /**
     * @var callable функция возвращающая url модели. Принимает аргументом модель для которой необходимо создать url
     */
    public $urlCreate;

    /**
     * @var callable функция для модификации запроса. Принимает аргументом \common\db\TActiveQuery
     */
    public $queryModify;

    /**
     * @var int глубина отображения
     */
    public $level = 1;

    /**
     * @var array массив атрибутов html тега
     */

    public $options = array();



    /**
     * @var array массив моделей
     */
    public $models;

    /**
     * @var string имя выводимого атрибута
     */
    public $labelAttr = "name";

    /**
     * @var int глубина родительского раздела
     */

    protected $parentLevel;

    /**
     * @inheritdoc
     */

    public function init()
    {

        if($this->models === null) {
            $class = $this->modelClass;

            $parent = $class::find()->published()->where(["id" => $this->parentId])->one();

            if (!$parent)
                return false;

            $this->parentLevel = $parent->level;

            $level = $parent->level + $this->level;

            $query = $parent->children()->published()->andWhere("level <= :level", [":level" => $level]);

            if (is_callable($this->queryModify)) {

                $func = $this->queryModify;

                $func($query);

            }

            $this->models = $query->all();

        }

    }

    /**
     * @inheritdoc
     */

    public function run()
    {

        if (!$this->isShow() OR empty($this->models))
            return false;

        return $this->render($this->tpl, [
            "models" => $this->models,
            "options" => $this->options,
            "parentLevel" => $this->parentLevel,
            "actClass" => $this->actClass,
            "urlCreate" => $this->urlCreate,
            "labelAttr" => $this->labelAttr,
        ]);

    }

    /**
     * Определяет является ли элемент активным
     * @var string $url
     * @return bool
     */

    public function isAct($url)
    {

        if (empty($url))
            return false;

        $request = Yii::$app->request;

        // Главная

        if ($url == "/") {

            if (empty($request->pathInfo))
                return true;

        } else {

            $pathinfo = "/" . $request->pathInfo . "/";

            if (strpos($pathinfo, $url) === 0)
                return true;

        }

        return false;

    }

}