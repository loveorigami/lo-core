<?php
namespace lo\core\db\fields;

/**
 * Class ParentListField
 * Поле для привязки древовидных моделей к родителю
 * @package lo\core\db\fields
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */

class ParentListField extends ListField
{

    /**
     * @inheritdoc
     */
    public $isRequired = true;

    /**
     * @inheritdoc
     */
    public $showInGrid = false;

    /**
     * @inheritdoc
     */
    public $showInExtendedFilter = false;

    /**
     * @inheritdoc
     */
    public $search = false;

    /**
     * @var string атрибут родительской модели отображаемый при детальном просмотре
     */
    public $viewAttr = "name";

    /**
     * @inheritdoc
     */
    protected function view()
    {
        $parent = parent::view();

        $parentModel = $this->model->getParents(1)->one();

        if($parentModel)
            $parent['value']=$parentModel->{$this->viewAttr};

        return $parent;
    }


}