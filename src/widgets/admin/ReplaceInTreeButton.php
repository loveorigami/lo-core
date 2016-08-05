<?php

namespace lo\core\widgets\admin;

use yii\helpers\Html;

/**
 * Class ReplaceInTreeButton
 * Виджет для перемещения древовидных моделей в иерархии
 * @package lo\core\widgets\admin
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ReplaceInTreeButton extends ActionButton
{

    /**
     * @var string текст на кнопке OK
     */

    public $labelOk = "OK";

    /**
     * @var array html атрибуты кнопки OK
     */

    public $optionsOk = [];

    /**
     * @inheritdoc
     */

    public function init()
    {

        if (!$this->visible)
            return "";

        if (empty($this->optionsOk["id"]))
            $this->optionsOk["id"] = static::ID_PREF . uniqid(rand());

        parent::init();

    }

    /**
     * @inheritdoc
     */

    public function run()
    {

        if (!$this->visible)
            return "";

        $str = Html::button($this->label, $this->options);

        $str .= "<span style='display: none;'>&nbsp;" . Html::dropDownList("parents", null, [], ["class" => "form-control"]) . "&nbsp;" . Html::button($this->labelOk, $this->optionsOk) . "</span>";

        return $str;

    }

    /**
     * Регистрируем обработчика клика по кнопке
     */

    protected function registerJs()
    {

        $this->view->registerJs("

             $('#{$this->options["id"]}').on('click', function(e){

                e.preventDefault();

                var self = $(this);

                var bl = self.next();

                var select = bl.find('select');

                var form = self.parents('form')

                var ids = [];

                var ch = form.find(\"[name='selection[]']:checked\");

                ch.each(function(){ ids.push($(this).val()); });

                if(!bl.is(':visible')) {

                    $.get('{$this->url}', {selection: ids}, function(data){ select.html(data); });

                } else {
                    select.html('');
                }

                form.find(\"[name='selection[]']\").attr('disabled', function(idx, oldAttr) { return !oldAttr; })

                bl.toggle();

             });

             $('#{$this->optionsOk["id"]}').on('click', function(){

                 var form = $(this).parents('form');

                 var select = $(this).prev();

                 form.append('<input type=\'hidden\' name=\'replace_parent_id\' value=\''+select.val()+'\' />');

                 form.find(\"[name='selection[]']\").attr('disabled', false);

                 form.attr('action', '{$this->url}');

                 form.submit();

             });

             ");

    }

}