<?php
namespace lo\core\widgets\admin;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\bootstrap\BootstrapPluginAsset;
use lo\core\db\MetaFields;

/**
 * Class Form
 * Форма модели для админки. Формируется на основе \common\db\MetaFields модели
 * @property array $tplDir директории где хранятся шаблоны
 * @package lo\core\widgets\admin
 */
class Form extends Widget
{

    /**
     * Преффикс идентификатора виджета
     */

    const FORM_ID_PREF = "form-";

    /**
     * @var \common\db\ActiveRecord модель
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
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
    ];

    /**
     * @var string идентификатор виджета
     */
    protected $id;

    /**
     * @var array директория с шаблонами
     */
    protected $_tplDir;

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
                "formOptions" => $formOptions,
                "id" => $this->id
            ]
        );
    }

    /**
     * @return array директории, где хранятся файлы шаблонов
     */
    public function getTplDir()
    {
        if ($this->_tplDir === null) {

            $widgetTpl = [$this->viewPath . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR];

            if (is_array($this->model->tplDir)) {
                foreach ($this->model->tplDir as $dir) {
                    $modelTpl[] = Yii::getAlias($dir);
                }
            } else {
                $modelTpl = [Yii::getAlias($this->model->tplDir)];
            }

/* $modelTpl = [Yii::$app->controller->module->basePath.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.Yii::$app->controller->id.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR];
            */
            $this->_tplDir = ArrayHelper::merge($modelTpl, $widgetTpl);
        }

        return $this->_tplDir;
    }

    /**
     * @var string шаблон для вкладки формы
     */
    public function getTplFile($key = MetaFields::DEFAULT_TAB)
    {
        foreach($this->tplDir as $dir){
            $file = $dir . $key . '.tpl';
            if (is_file($file)) return $this->renderFile($file);
        };
        return false;
    }

}