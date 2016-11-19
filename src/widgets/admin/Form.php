<?php
namespace lo\core\widgets\admin;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\bootstrap\BootstrapPluginAsset;
use lo\core\db\MetaFields;

/**
 * Class Form
 * Форма модели для админки. Формируется на основе \lo\core\db\MetaFields модели
 * @property array $tplDir директории где хранятся шаблоны
 * @package lo\core\widgets\admin
 */
class Form extends Widget
{
    /** Преффикс идентификатора виджета */
    const FORM_ID_PREF = "form-";

    /** @var \lo\core\db\ActiveRecord модель */
    public $model;

    /** @var array валидируемые модели */
    public $models = [];

    /** @var array параметры \yii\widgets\ActiveForm */
    public $formOptions = [];

    /** @var string шаблон */
    public $tpl = "form";

    /** @var array параметры \yii\widgets\ActiveForm по умолчанию */

    protected $defaultFormOptions = [
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'errorSummaryCssClass' => 'alert alert-danger alert-dismissible'
    ];

    /** @var string идентификатор виджета */
    protected $id;

    /** @var array директория с шаблонами */
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
        $models = (is_array($this->models)) ? $this->models : [$this->models];

        return $this->render($this->tpl, [
                "model" => $this->model,
                "formOptions" => $formOptions,
                "id" => $this->id,
                'models' => ArrayHelper::merge([$this->model], $models)
            ]
        );
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
            $formTpl = [$ctr->module->basePath.$DS.'views'.$DS.$ctr->id.$DS.'tpl'.$DS];
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
        foreach($this->getTplDir() as $dir){
            $file = $dir . $key . '.tpl';
            if (is_file($file)) {
                return $this->renderFile($file);
            }
        };
        return null;
    }

}