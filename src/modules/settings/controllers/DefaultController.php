<?php
namespace lo\core\modules\settings\controllers;

use lo\core\actions\crud\Settings;
use lo\core\modules\settings\models\FormModel;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=> Settings::class,
                'keys' => [
                    'frontend.maintenance' => [
                        'label' => Yii::t('backend', 'Frontend maintenance mode'),
                        'type' => FormModel::TYPE_DROPDOWN,
                        'items' => [
                            'disabled' => Yii::t('backend', 'Disabled'),
                            'enabled' => Yii::t('backend', 'Enabled')
                        ]
                    ],
                    'backend.theme-skin' => [
                        'label' => Yii::t('backend', 'Backend theme'),
                        'type' => FormModel::TYPE_DROPDOWN,
                        'items' => [
                            'skin-black' => 'skin-black',
                            'skin-blue' => 'skin-blue',
                            'skin-green' => 'skin-green',
                            'skin-purple' => 'skin-purple',
                            'skin-red' => 'skin-red',
                            'skin-yellow' => 'skin-yellow',
                            'skin-black-light' => 'skin-black-light',
                            'skin-blue-light' => 'skin-blue-light',
                            'skin-green-light' => 'skin-green-light',
                            'skin-purple-light' => 'skin-purple-light',
                            'skin-red-light' => 'skin-red-light',
                            'skin-yellow-light' => 'skin-yellow-light'
                        ]
                    ],
                    'backend.layout-fixed' => [
                        'label' => Yii::t('backend', 'Fixed backend layout'),
                        'type' => FormModel::TYPE_CHECKBOX
                    ],
                    'backend.layout-boxed' => [
                        'label' => Yii::t('backend', 'Boxed backend layout'),
                        'type' => FormModel::TYPE_CHECKBOX
                    ],
                    'backend.layout-collapsed-sidebar' => [
                        'label' => Yii::t('backend', 'Backend sidebar collapsed'),
                        'type' => FormModel::TYPE_CHECKBOX
                    ]
                ]
            ],
        ];
    }

}
