<?php
/**
 * @var $model \lo\core\modules\core\models\TimelineEvent
 */
use yii\helpers\Html;

?>
<i class="fa fa-comment bg-aqua"></i>
<div class="timeline-item">
    <span class="time">
        <i class="fa fa-clock-o"></i>
        <?php echo Yii::$app->formatter->asRelativeTime($model->created_at) ?>
    </span>

    <h3 class="timeline-header">
        <?= Html::a(
            Yii::t('backend', 'You have new comment!'),
            ['/comments/item/update', 'id' => $model->data['id']],
            ['data-pjax' => 0]
        ) ?>
    </h3>

    <div class="timeline-body">
        <?php echo Yii::t('backend', 'New comment from {user} was added at {date}', [
            'user' => '<span class="label bg-purple">' . $model->data['name'] . '</span>',
            'date' => Yii::$app->formatter->asDatetime($model->data['date'])
        ]) ?>
        <div class="clearfix"></div>
        <?= $model->data['text'] ?>
    </div>

    <div class="timeline-footer">
        <?= Html::a(
            Yii::t('backend', 'View'),
            ['/comments/item/update', 'id' => $model->data['id']],
            [
                'class' => 'btn btn-success btn-xs',
                'data-pjax' => 0
            ]
        ) ?>
        {delete}
    </div>
</div>

