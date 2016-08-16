<?php
namespace lo\core\widgets\admin;

use lo\core\db\ActiveRecord;
use lo\core\traits\AccessRouteTrait;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class CrudLinks
 * Класс для отображения ссылок на CRUD действия
 * @package lo\core\widgets\admin
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CrudLinks extends Widget
{
    use AccessRouteTrait;

    /** Константы CRUD действий */
    const CRUD_LIST = "list";
    const CRUD_VIEW = "view";

    /** @var string действие для которого отобразить кнопки (self::CRUD_LIST|self::CRUD_VIEW) */
    public $action;

    /** @var ActiveRecord модель */
    public $model;

    /** @var array массив дополнительных параметров для url */
    public $urlParams = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        $buttons = $this->getButtons()[$this->action];

        $html = '';

        foreach ($buttons AS $button) {
            if (Yii::$app->user->can($button['permission'], ['model' => $this->model]))
                $html .= Html::a($button["label"], $button["url"], $button["options"]) . "\n ";
        }

        return $html;
    }

    /**
     * Возвращает описание ссылок !Yii::$app->user->can($this->baseRoute . '/update', ['model'=>$model])
     * @return array
     */
    public function getButtons()
    {
        return $buttons = [

            self::CRUD_LIST => [
                [
                    'label' => Yii::t('core', 'Create'),
                    'url' => array_merge(['create'], $this->urlParams),
                    'options' => ['class' => 'btn btn-success pull-right'],
                    'permission' => $this->access('create'),
                ]
            ],

            self::CRUD_VIEW => [
                [
                    'label' => Yii::t('core', 'Update'),
                    'url' => array_merge(['update', 'id' => $this->model->id], $this->urlParams),
                    'options' => ['class' => 'btn btn-primary  pull-right'],
                    'permission' => $this->access('update'),
                ],
                [
                    'label' => Yii::t('core', 'Delete'),
                    'url' => array_merge(['delete', 'id' => $this->model->id], $this->urlParams),
                    'options' => ['class' => 'btn btn-danger pull-right',
                        'data' => [
                            'confirm' => Yii::t('core', 'Are you sure?'),
                            'method' => 'post',
                        ],
                    ],
                    'permission' => $this->access('delete'),
                ],
            ],
        ];
    }
}