<?php
namespace lo\core\widgets\admin;

use lo\core\db\ActiveRecord;
use lo\core\traits\AccessRouteTrait;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class Grid
 * Грид для админки. Формируется на основе \lo\core\db\MetaFields модели
 * @property array $rowButtons кнопки действий строк грида
 * @property array $groupButtons кнопки групповых операций
 * @property string $baseRoute базовая часть маршрута для формировнаия url действий
 * @package lo\core\widgets\admin
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Grid extends Widget
{
    use AccessRouteTrait;

    /** Преффикс идентификатора грида */
    const GRID_ID_PREF = "grid-";

    /** Суфикс иденификатора виджета Pjax */
    const PJAX_SUF = "-pjax";

    /** @var ActiveRecord модель */
    public $model;

    /** @var ActiveDataProvider провайдер данных */
    public $dataProvider;

    /** @var string имя параметра передаваемого расширенным фильтром */
    public $extFilterParam = "extendedFilter";

    /** @var array кнопки строк грида */
    protected $_rowButtons;

    /**@var array дополнительные пользовательские колонки */
    public $userColumns = [];

    /** @var bool вывод древовидных моделей */
    public $tree = false;

    /** @var bool вывод древовидных моделей */
    public $actions;

    /** @var string шаблон */
    public $tpl = "grid";

    /** @var array кнопки групповых операций */
    protected $_groupButtons;

    /** @var string идентификатор виджета */
    protected $id;

    /** @var string идентификатор виджета PJAX */
    protected $pjaxId;

    /**
     * @return array
     */
    public function getRowButtons()
    {
        if ($this->_rowButtons === null) {
            $this->_rowButtons = $this->defaultRowButtons();
        }

        return $this->_rowButtons;
    }

    /**
     * @param array $rowButtons
     */
    public function setRowButtons($rowButtons)
    {
        $this->_rowButtons = ArrayHelper::merge($this->defaultRowButtons(), $rowButtons);
    }


    public function init()
    {
        $model = $this->model;
        $this->id = strtolower(self::GRID_ID_PREF . str_replace("\\", "-", $model::className()));
        $this->pjaxId = $this->id . self::PJAX_SUF;
        $this->view->registerCss(".grid-checkbox-disabled input[type='checkbox'] { display:none; }");
    }

    /**
     * Запуск виджета
     * @return string|void
     */
    public function run()
    {
        return $this->render($this->tpl, [
            "model" => $this->model,
            "dataProvider" => $this->dataProvider,
            "columns" => $this->getColumns(),
            "groupButtons" => $this->getGroupButtons(),
            "id" => $this->id,
            "pjaxId" => $this->pjaxId,
        ]);
    }

    /**
     * Возвращает описание колонок
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [
                'class' => 'yii\grid\CheckboxColumn',
                /**
                 * 'contentOptions' => function ($model, $key, $index, $gridView) {
                 * $arr = [];
                 * if (
                 * !Yii::$app->user->can($this->access('update'), ['model' => $model]) AND
                 * !Yii::$app->user->can($this->access('delete'), ['model' => $model])
                 * ) {
                 * $arr = ["class" => "grid-checkbox-disabled"];
                 * }
                 * return $arr;
                 * },
                 */
                'headerOptions' => ['style' => 'width: 30px;']
            ],
        ];

        $fields = $this->model->getMetaFields()->getFields();

        foreach ($fields AS $field) {
            $grid = $field->getGrid();

            if ($field->showInGrid AND $grid)
                $columns[] = $grid;
        }

        $columns = ArrayHelper::merge($columns, $this->userColumns);
        $columns[] = $this->getRowButtons();

        return $columns;
    }

    /**
     * Возвращает настройки по умолчанию кнопок действий над моделями
     * @return array
     */
    public function defaultRowButtons()
    {

        $arr = [
            'class' => 'yii\grid\ActionColumn',
            'headerOptions' => ['style' => 'width: 95px;']
        ];

        $buttonsDefault = ['view', 'update', 'delete'];
        $buttonsTree = ['up', 'down', 'enter'];

        if ($this->actions) {
            $actions = $this->actions;
        } else {
            if ($this->tree) {
                $arr['template'] = '{enter} {up} {down}<div class="clear_small2"></div>{update} {view} {delete}';
                $actions = array_merge($buttonsTree, $buttonsDefault);
            } else {
                $actions = $buttonsDefault;
            }
        }

        $preset = $this->getButtonsFromPreset($actions);

        $arr['buttons'] = $preset['buttons'];
        $arr['template'] = $preset['template'];

        return $arr;
    }

    /**
     * @param $preset
     * @return array
     */
    protected function getButtonsFromPreset($preset)
    {
        $buttons = $this->buttonsPreset();
        $row = ['buttons' => [], 'template' => ''];

        foreach ($preset as $button) {
            if (isset($buttons[$button])) {
                $row['buttons'][$button] = $buttons[$button];
                $row['template'] = $row['template'] . ' {' . $button . '}';
            }
        }
        return $row;
    }

    /**
     * @return array
     */
    protected function buttonsPreset()
    {
        $js = function ($u) {
            return '$.get("' . $u . '", function(){ $.pjax.reload({container: "#' . $this->pjaxId . '", timeout: false}); }); return false;';
        };

        return [
            'view' => function ($url, $model) use ($js) {
                $url = Url::toRoute([$this->baseRoute . '/view', 'id' => $model->id]);
                //if (Yii::$app->user->can($this->access('view'), ['model' => $model]))
                return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-eye-open']), ['data-pjax' => 0, 'href' => $url, 'title' => Yii::t('core', 'View'), 'class' => 'btn btn-xs btn-primary']);
            },

            'update' => function ($url, $model) use ($js) {
                $url = Url::toRoute([$this->baseRoute . '/update', 'id' => $model->id]);
                //if (Yii::$app->user->can($this->access('update'), ['model' => $model]))
                return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-pencil']), ['data-pjax' => 0, 'href' => $url, 'title' => Yii::t('core', 'Update'), 'class' => 'btn btn-xs btn-primary']);
            },

            'delete' => function ($url, $model) use ($js) {
                $url = Url::toRoute([$this->baseRoute . '/delete', 'id' => $model->id]);
                //if (Yii::$app->user->can($this->access('delete'), ['model' => $model]))
                return Html::a(Html::tag('i', '', ['class' => 'glyphicon glyphicon-trash']), $url, ['data-pjax' => 0, 'data-method' => 'post', 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'), 'title' => Yii::t('core', 'Delete'), 'class' => 'btn btn-xs btn-danger']);
            },

            'up' => function ($url, $model) use ($js) {
                $url = Url::toRoute([$this->baseRoute . '/up', 'id' => $model->id]);
                //if (Yii::$app->user->can($this->access('update'), ['model' => $model]))
                return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-arrow-up']), ['data-pjax' => 0, 'onClick' => $js($url), 'href' => '#', 'title' => Yii::t('core', 'Up'), 'class' => 'btn btn-xs btn-success']);
            },

            'down' => function ($url, $model) use ($js) {
                $url = Url::toRoute([$this->baseRoute . '/down', 'id' => $model->id]);
                //if (Yii::$app->user->can($this->access('update'), ['model' => $model]))
                return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-arrow-down']), ['data-pjax' => 0, 'onClick' => $js($url), 'href' => '#', 'title' => Yii::t('core', 'Down'), 'class' => 'btn btn-xs btn-warning']);
            },

            'enter' => function ($url, $model) {
                if (Yii::$app->user->can($this->access('view'), ['model' => $model])) {
                    $childs = count($model->children(1)->all());
                    if ($childs) {
                        $url = Url::toRoute(["/" . Yii::$app->controller->route, "parent_id" => $model->id]);
                        return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-log-in']), ['data-pjax' => 0, 'href' => $url, 'title' => Yii::t('core', 'Enter') . ' ' . $childs, 'class' => 'btn btn-xs btn-primary']);
                    } else {
                        $url = Url::toRoute([$this->baseRoute . '/create', 'parent_id' => $model->id]);
                        return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']), ['data-pjax' => 0, 'href' => $url, 'title' => Yii::t('core', 'Create'), 'class' => 'btn btn-xs btn-primary']);
                    }
                }
            },
        ];
    }

    /**
     * Возвращает массив кнопок групповых операций
     * @return array
     */
    public function getGroupButtons()
    {
        if ($this->_groupButtons === null) {
            $this->_groupButtons = $this->defaultGroupButtons();
        }
        return $this->_groupButtons;
    }

    /**
     * Установка кнопок групповых операций
     * @param array $buttons параметры виджетов кнопок
     * [
     *  "delete"=>[
     *      "class"=>\lo\core\widgets\admin\ActionButton::getClass(),
     *      "label"=>Yii::t("core", "Delete"),
     *      "url"=>"groupdelete",
     *  ],
     * ]
     */
    public function setGroupButtons(Array $buttons)
    {
        $this->_groupButtons = ArrayHelper::merge($this->defaultGroupButtons(), $buttons);
    }

    /**
     * Кнопки групповых операций по умолчанию
     * @return array
     */

    protected function defaultGroupButtons()
    {
        $model = $this->model;
        $arr = [
            "delete" => [
                "class" => ActionButton::class,
                "label" => Yii::t('core', 'Delete'),
                "visible" => Yii::$app->user->can($this->access('delete'), ['model' => $model]),
                "options" => [
                    'id' => 'group-delete',
                    'class' => 'btn btn-danger',
                ],
                "route" => $this->baseRoute . "/groupdelete",
            ],
        ];

        if ($this->tree AND !Yii::$app->request->get($this->extFilterParam)) {
            $arr["replace"] = [
                "class" => ReplaceInTreeButton::class,
                "visible" => Yii::$app->user->can($this->access('update'), ['model' => $model]),
                "label" => Yii::t('core', 'Replace'),
                "options" => [
                    'id' => 'group-replace',
                    'class' => 'btn btn-primary',
                ],
                "optionsOk" => [
                    'id' => 'group-replace-ok',
                    'class' => 'btn btn-primary',
                ],
                "route" => $this->baseRoute . "/replace",
            ];
        }
        return $arr;
    }
}