<?php
/**
 * @var \yii\data\ArrayDataProvider $dataProvider
 */

use yii\bootstrap\Button;
use lo\wshell\widgets\ShellWidget;

$this->title = Yii::t('backend', 'Shell');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="shell-index">

    <?php
    echo Button::widget([
        'label' => Yii::t('backend', 'Start migrate'),
        'options' => [
            'class' => 'btn-success',
            'data-shell-widget-run' => 'migrate-widget',
        ],
    ]);

    echo ShellWidget::widget([
        'id' => 'migrate-widget',
        'route' => ['migrate'],
        'autorun' => false,
        //'initialContent' => Yii::t('app', 'Ready and waiting...'),
        'clientOptions' => [
            //custom client options here
        ],
    ]);

    /**
     * Clear dir
     */
    echo Button::widget([
        'label' => Yii::t('backend', 'Clear dir'),
        'options' => [
            'class' => 'btn-success',
            'data-shell-widget-run' => 'dir',
        ],
    ]);

    echo ShellWidget::widget([
        'id' => 'dir',
        'route' => ['clear-dir'],
        'autorun' => false,
    ]);

    ?>

</div>
