<?php

namespace lo\core\widgets\admin;

use lo\core\db\ActiveRecord;
use lo\core\db\tree\TActiveRecord;
use lo\core\helpers\IframeHelper;
use lo\core\helpers\PkHelper;
use lo\core\helpers\RbacHelper;
use lo\core\rbac\MdmHelper;
use lo\core\traits\AccessRouteTrait;
use Yii;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\grid\CheckboxColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class Grid
 * Грид для админки. Формируется на основе \lo\core\db\MetaFields модели
 *
 * @property array  $rowButtons   кнопки действий строк грида
 * @property array  $groupButtons кнопки групповых операций
 * @property array  $columns
 * @property string $baseRoute    базовая часть маршрута для формировнаия url действий
 * @property bool   $useGroupButtons
 * @package lo\core\widgets\admin
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class Grid extends Widget
{
    use AccessRouteTrait;

    /** Преффикс идентификатора грида */
    protected const GRID_ID_PREF = 'grid-';

    /** Суфикс иденификатора виджета Pjax */
    protected const PJAX_SUF = '-pjax';

    /** @var ActiveRecord модель */
    public $model;

    /** @var ActiveDataProvider провайдер данных */
    public $dataProvider;

    /** @var string имя параметра передаваемого расширенным фильтром */
    public $extFilterParam = 'extendedFilter';

    /** @var array кнопки строк грида */
    protected $_rowButtons;

    /**@var array дополнительные пользовательские колонки */
    public $userColumns = [];

    /** @var bool вывод древовидных моделей */
    public $tree = false;

    /** @var array action column */
    public $actions;

    /** @var string шаблон */
    public $tpl = 'grid';

    public $updatePermission = RbacHelper::B_UPDATE;
    public $deletePermission = RbacHelper::B_DELETE;

    public $rowOptions;
    public $showGroupButtons = true;
    public $showGroupActivate = false;
    public $showGroupDeactivate = false;
    public $showRowButtons = true;

    /** @var array кнопки групповых операций */
    protected $_groupButtons;

    /** @var string идентификатор виджета */
    protected $id;

    /** @var string идентификатор виджета PJAX */
    protected $pjaxId;

    /**
     * @return array
     */
    public function getRowButtons(): array
    {
        if ($this->_rowButtons === null) {
            $this->_rowButtons = $this->defaultRowButtons();
        }

        return $this->_rowButtons;
    }

    /**
     * @param array $rowButtons
     */
    public function setRowButtons($rowButtons): void
    {
        $this->_rowButtons = ArrayHelper::merge($this->defaultRowButtons(), $rowButtons);
    }


    public function init()
    {
        $model = $this->model;
        $this->id = strtolower(self::GRID_ID_PREF . str_replace("\\", "-", \get_class($model)));
        $this->pjaxId = $this->id . self::PJAX_SUF;
        $this->view->registerCss(".grid-checkbox-disabled input[type='checkbox'] { display:none; }");
    }

    /**
     * Запуск виджета
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        return $this->render($this->tpl, [
            'model' => $this->model,
            'dataProvider' => $this->dataProvider,
            'rowOptions' => $this->rowOptions,
            'columns' => $this->getColumns(),
            'groupButtons' => $this->getGroupButtons(),
            'id' => $this->id,
            'pjaxId' => $this->pjaxId,
        ]);
    }

    /**
     * Возвращает описание колонок
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function getColumns(): array
    {
        $columns = [];

        if ($this->showGroupButtons) {
            $columns[] = [
                'class' => CheckboxColumn::class,
                'contentOptions' => function ($model) {
                    $arr = [];
                    if (
                    !RbacHelper::canUser($this->deletePermission, $model)
                    ) {
                        $arr = [
                            'class' => 'grid-checkbox-disabled',
                        ];
                    }

                    return $arr;
                },
                'checkboxOptions' => function ($model) {
                    return ['value' => PkHelper::encode($model)];
                },
                'headerOptions' => ['style' => 'width: 30px;'],
            ];
        }

        $fields = $this->model->getMetaFields()->getFields();

        foreach ($fields AS $field) {
            $grid = $field->getGrid();

            if (isset($grid['columns'])) {
                foreach ($grid['columns'] as $col) {
                    $columns[] = $col;
                }
            } else {
                if ($field->showInGrid AND $grid) {
                    $columns[] = $grid;
                }
            }
        }

        $columns = ArrayHelper::merge($columns, $this->userColumns);

        if ($this->showRowButtons) {
            $columns[] = $this->getRowButtons();
        }

        return $columns;
    }

    /**
     * Возвращает настройки по умолчанию кнопок действий над моделями
     *
     * @return array
     */
    public function defaultRowButtons()
    {

        $arr['class'] = 'yii\grid\ActionColumn';

        $buttonsDefault = ['update', 'delete'];
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
        $arr['template'] = MdmHelper::filterActionColumn($preset['template']);
        $arr['urlCreator'] = function ($action, $model) {
            /** function ($action, $model, $key, $index) */
            return Url::to([$action, 'id' => PkHelper::encode($model)]);
        };
        $width = count($preset['buttons']) < 3 ? 85 : 100;
        $arr['headerOptions'] = ['style' => 'min-width: ' . $width . 'px;'];

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
            'view' => function ($url) {
                return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-eye-open']),
                    ['data-pjax' => 0, 'href' => $url, 'title' => Yii::t('core', 'View'), 'class' => 'btn btn-xs btn-primary']);
            },

            'update' => function ($url, $model) {
                if (RbacHelper::canUser($this->updatePermission, $model)) {
                    return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-pencil']),
                        ['data-pjax' => 0, 'href' => $url, 'title' => Yii::t('core', 'Update'), 'class' => 'btn btn-xs btn-primary ' . IframeHelper::IFRAME_SELECTOR]);
                }

                return null;
            },

            'delete' => function ($url, $model) {
                if (RbacHelper::canUser($this->deletePermission, $model)) {
                    return Html::a(Html::tag('i', '', ['class' => 'glyphicon glyphicon-trash']), $url, [
                        'data-pjax' => 0,
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'title' => Yii::t('core', 'Delete'),
                        'class' => 'btn btn-xs btn-danger',
                    ]);
                }

                return null;
            },

            'up' => function ($url) use ($js) {
                return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-arrow-up']),
                    ['data-pjax' => 0, 'onClick' => $js($url), 'href' => '#', 'title' => Yii::t('core', 'Up'), 'class' => 'btn btn-xs btn-default']);
            },

            'down' => function ($url) use ($js) {
                return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-arrow-down']),
                    ['data-pjax' => 0, 'onClick' => $js($url), 'href' => '#', 'title' => Yii::t('core', 'Down'), 'class' => 'btn btn-xs btn-default']);
            },

            'enter' => function ($url, $model) {
                /** @var TActiveRecord $model */
                $childs = \count($model->children);
                if ($childs) {
                    $url = Url::toRoute(['/' . Yii::$app->controller->route, 'parent_id' => PkHelper::encode($model)]);

                    return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-log-in']),
                        ['data-pjax' => 0, 'href' => $url, 'title' => Yii::t('core', 'Enter') . ' ' . $childs, 'class' => 'btn btn-xs btn-primary']);
                }

                $url = Url::toRoute([$this->baseRoute . '/create', 'parent_id' => PkHelper::encode($model)]);

                return Html::tag('a', Html::tag('i', '', ['class' => 'glyphicon glyphicon-plus']),
                    ['data-pjax' => 0, 'href' => $url, 'title' => Yii::t('core', 'Create'), 'class' => 'btn btn-xs btn-default']);
            },
        ];
    }

    /**
     * Возвращает массив кнопок групповых операций
     *
     * @return array
     */
    public function getGroupButtons(): array
    {
        if ($this->_groupButtons === null) {
            $this->_groupButtons = $this->defaultGroupButtons();
        }

        return $this->_groupButtons;
    }

    /**
     * Установка кнопок групповых операций
     *
     * @param array $buttons параметры виджетов кнопок
     *                       [
     *                       'delete'=>[
     *                       'class'=>\lo\core\widgets\admin\ActionButton::getClass(),
     *                       'label'=>Yii::t('core', 'Delete'),
     *                       'url'=>'groupdelete',
     *                       ],
     *                       ]
     */
    public function setGroupButtons(Array $buttons)
    {
        $this->_groupButtons = ArrayHelper::merge($this->defaultGroupButtons(), $buttons);
    }

    /**
     * Кнопки групповых операций по умолчанию
     *
     * @return array
     */

    protected function defaultGroupButtons(): array
    {
        if (!$this->showGroupButtons) {
            return [];
        }

        $arr['activate'] = [
            'class' => ActionButton::class,
            'label' => Yii::t('core', 'Group activate'),
            'visible' => $this->showGroupActivate,
            'options' => [
                'id' => 'group-activate',
                'class' => 'btn btn-primary',
            ],
            'route' => $this->baseRoute . '/groupactivate',
        ];

        $arr['deactivate'] = [
            'class' => ActionButton::class,
            'label' => Yii::t('core', 'Group deactivate'),
            'visible' => $this->showGroupDeactivate,
            'options' => [
                'id' => 'group-deactivate',
                'class' => 'btn btn-primary',
            ],
            'route' => $this->baseRoute . '/groupdeactivate',
        ];

        if ($this->tree AND !Yii::$app->request->get($this->extFilterParam)) {
            $arr['replace'] = [
                'class' => ReplaceInTreeButton::class,
                'label' => Yii::t('core', 'Replace'),
                'options' => [
                    'id' => 'group-replace',
                    'class' => 'btn btn-primary',
                ],
                'optionsOk' => [
                    'id' => 'group-replace-ok',
                    'class' => 'btn btn-primary',
                ],
                'route' => $this->baseRoute . '/replace',
            ];
        }

        $arr['delete'] = [
            'class' => ActionButton::class,
            'label' => Yii::t('core', 'Delete'),
            'options' => [
                'id' => 'group-delete',
                'class' => 'btn btn-danger',
            ],
            'route' => $this->baseRoute . '/groupdelete',
        ];

        return $arr;
    }
}
