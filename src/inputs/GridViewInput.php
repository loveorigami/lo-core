<?php

namespace lo\core\inputs;

use lo\core\helpers\FA;
use lo\core\helpers\IframeHelper;
use lo\core\helpers\RbacHelper;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use lo\core\widgets\awcheckbox\AwesomeCheckbox;

/**
 * Class CrudInput
 *
 * @package lo\core\inputs
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 *    "_bookings" => [
 * "definition" => [
 * "class" => fields\ManyChildField::class,
 * "inputClassOptions" => [
 * 'createParams' => ['rid' => $this->owner->getPrimaryKey()],
 * 'route' => '/base/room-booking',
 * 'pjaxContainer' => '#grid-crud-pjax',
 * 'gridViewOptions' => [
 * 'dataProvider' => $this->getBookingListProvider(),
 * 'columns' => [
 * 'date_from:date',
 * 'date_to:date'
 * ]
 * ]
 * ],
 * "title" => Yii::t('backend', 'Stop online'),
 * "relationName" => 'bookings',
 * "gridAttr" => 'date_from',
 * ],
 * "params" => [$this->owner, "_bookings"]
 * ],
 */
class GridViewInput extends BaseInput
{
    /**
     * Настройки по умолчанию
     *
     * @var array
     */
    protected $defaultOptions = [
        'type' => AwesomeCheckbox::TYPE_CHECKBOX,
        'style' => AwesomeCheckbox::STYLE_PRIMARY,
    ];

    public $gridViewOptions;
    public $createParams = [];
    public $pjaxContainer = '#grid-crud-pjax';
    public $route;

    public $updatePermission = RbacHelper::B_UPDATE;
    public $deletePermission = RbacHelper::B_DELETE;

    /**
     * Формирование Html кода поля для вывода в форме
     *
     * @param ActiveForm $form    объект форма
     * @param array      $options массив html атрибутов поля
     * @param bool|int   $index   индекс модели при табличном вводе
     * @return string
     */
    public function renderInput(ActiveForm $form, Array $options = [], $index = false)
    {

        if ($this->getModel()->isNewRecord) {
            return null;
        }

        $gridViewOptions = ArrayHelper::merge(
            [
                'pager' => false,
                'layout' => '{items}',
            ],
            $this->gridViewOptions,
            [
                'columns' => [
                    [
                        'class' => ActionColumn::class,
                        'template' => '{update}  {delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return RbacHelper::canUser($this->updatePermission, $model) ?
                                    IframeHelper::a(
                                        FA::i(FA::_PENCIL),
                                        [$this->route . '/update', 'id' => $model->id],
                                        [
                                            'class' => 'btn btn-primary btn-xs modal-crud',
                                            'data-pjax' => '0',
                                        ]
                                    ) : '';
                            },
                            'delete' => function ($url, $model) {
                                return RbacHelper::canUser($this->deletePermission, $model) ?
                                    Html::a(
                                        FA::i(FA::_TRASH),
                                        [$this->route . '/delete', 'id' => $model->id],
                                        [
                                            'class' => 'btn btn-danger btn-xs',
                                            'data' => [
                                                'pjax' => '0',
                                                'method' => 'post',
                                                'confirm' => 'Действительно удалить?',
                                            ],
                                        ]
                                    ) : '';
                            },
                        ],
                    ],
                ],
            ]
        );

        $str[] = Html::label($this->getModel()->getAttributeLabel($this->getAttr()));

        $str[] = Html::a(
            FA::i(FA::_PLUS) . ' добавить',
            ArrayHelper::merge([$this->route . '/create'], $this->createParams),
            ['class' => 'btn btn-success btn-xs pull-right modal-crud']
        );

        $str[] = GridView::widget($gridViewOptions);

        return implode("\r\n", $str);
    }
}
