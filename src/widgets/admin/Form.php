<?php

namespace lo\core\widgets\admin;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\bootstrap\BootstrapPluginAsset;
use lo\core\db\MetaFields;
use yii\helpers\Html;

/**
 * Class Form
 * Форма модели для админки. Формируется на основе \lo\core\db\MetaFields модели
 *
 * @property array $tplDir директории где хранятся шаблоны
 * @package lo\core\widgets\admin
 */
class Form extends Widget
{
    public const BTN_SAVE = 'save';
    public const BTN_APPLY = 'apply';
    public const BTN_CANCEL = 'cancel';

    /**
     * Преффикс идентификатора виджета
     */
    const FORM_ID_PREF = "form-";

    /**
     * @var \lo\core\db\ActiveRecord модель
     */
    public $model;

    /**
     * @var array параметры \yii\widgets\ActiveForm
     */
    public $formOptions = [];

    /**
     * @var string шаблон
     */
    public $tpl = "form";

    /**
     * @var array параметры \yii\widgets\ActiveForm по умолчанию
     */
    protected $defaultFormOptions = [
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'errorSummaryCssClass' => 'alert alert-danger alert-dismissible',
    ];

    /**
     * @var string идентификатор виджета
     */
    protected $id;

    /**
     * @var array идентификатор виджета
     */
    public $crudButtons = [];

    /**
     * @var array
     */
    public $crudButtonsTpl = [
        self::BTN_SAVE,
        self::BTN_APPLY,
        self::BTN_CANCEL,
    ];

    /**
     * @var array директория с шаблонами
     */
    protected $_tplDir;


    /**
     * init
     */
    public function init()
    {
        $model = $this->model;
        $this->id = strtolower(self::FORM_ID_PREF . str_replace("\\", "-", $model::className()));

        BootstrapPluginAsset::register($this->view);
    }

    public function run()
    {
        $formOptions = array_merge($this->defaultFormOptions, $this->formOptions);

        return $this->render($this->tpl, [
                "model" => $this->model,
                "crudButtons" => $this->getCrudButtons(),
                "formOptions" => $formOptions,
                "id" => $this->id,
            ]
        );
    }

    /**
     * @return array
     */
    protected function getCrudButtons()
    {
        return ArrayHelper::merge($this->getDefaultCrudButtons(), $this->crudButtons);
    }

    /**
     * @return array
     */
    protected function getDefaultCrudButtons(): array
    {
        $btns = [];

        if (ArrayHelper::isIn(self::BTN_SAVE, $this->crudButtonsTpl)) {
            $btns[self::BTN_SAVE] = Html::submitButton(Yii::t('core', 'Save'), ['class' => 'btn btn-success form-save']);
        }

        if (ArrayHelper::isIn(self::BTN_APPLY, $this->crudButtonsTpl)) {
            $btns[self::BTN_APPLY] = Html::submitButton(Yii::t('core', 'Apply'), ['class' => 'btn btn-primary form-apply']);
        }

        if (ArrayHelper::isIn(self::BTN_CANCEL, $this->crudButtonsTpl)) {
            $btns[self::BTN_CANCEL] = Html::button(Yii::t('core', 'Cancel'), ['class' => 'btn btn-default form-cancel']);
        }

        return $btns;
    }

    /**
     * @return array директории, где хранятся файлы шаблонов
     */
    public function getTplDir()
    {
        if ($this->_tplDir === null) {
            $DS = DIRECTORY_SEPARATOR;
            $ctr = Yii::$app->controller;
            $widgetTpl = [$this->viewPath . $DS . 'tpl' . $DS];
            $formTpl = [$ctr->module->basePath . $DS . 'views' . $DS . $ctr->id . $DS . 'tpl' . $DS];
            $this->_tplDir = ArrayHelper::merge($formTpl, $widgetTpl);
        }

        return $this->_tplDir;
    }

    /**
     * @param string $key шаблон для вкладки формы
     * @return null|string
     */
    public function getTplFile($key = MetaFields::DEFAULT_TAB)
    {
        foreach ($this->getTplDir() as $dir) {
            $file = $dir . $key . '.tpl';
            if (is_file($file)) {
                return $this->renderFile($file);
            }
        };

        return null;
    }

}
