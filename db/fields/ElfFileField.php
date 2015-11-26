<?php

namespace lo\core\db\fields;

/**
 * Class HtmlField
 * Поле WYSIWYG редактора. Использует CKEditor
 * @package lo\core\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class ElfFileField extends Field
{
    /**
     * @inheritdoc
     */

    public $showInGrid = false;
    public $showInFilter = false;
    public $isRequired = false;
    public $editInGrid = false;
    public $showInExtendedFilter = false;

    public $inputClass = '\lo\core\inputs\ElfFileInput';


    /**
     * @inheritdoc
     */
    protected function grid()
    {
        $grid = parent::grid();
        $grid['format'] = 'html';
        $grid['value'] = function ($model, $index, $widget) {
                return $this->renderFilesGridView($model->{$this->attr});
        };
        return $grid;
    }

    /**
     * Возвращает строку для отображения файлов в гриде
     * @param array $files массив с описанием файлов
     * @return string
     */

    protected function renderFilesGridView($files)
    {
       return $this->renderFilesView($files);
    }

    /**
     * @inheritdoc
     */
    protected function view()
    {
        $view = parent::view();
        if (is_array($this->model->{$this->attr})) {
            $view["value"] = $this->renderFilesView($this->model->{$this->attr});
            $view["format"] = "html";
        }
        return $view;
    }

    /**
     * Возвращает строку для отображения файлов при детальном просмотре
     * @param array $files массив с описанием файлов
     * @return string
     */

    protected function renderFilesView($files)
    {
        $html = "";
        foreach ($files AS $file)
            $html .= '<a href="' . $file["file"] . '"><span class="glyphicon glyphicon-download"></span></a>' . "\n";

        return $html;
    }

    /**
     * @inheritdoc
     */

    protected function defaultGridFilter()
    {
        return false;
    }

}