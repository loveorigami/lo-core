<?php
/**
 * @var $model \lo\core\modules\core\models\TimelineEvent
 */
?>
<div class="timeline-item">
    <span class="time">
        <i class="fa fa-clock-o"></i>
        <?php echo Yii::$app->formatter->asRelativeTime($model->created_at) ?>
    </span>

    <h3 class="timeline-header">
        <?php echo Yii::t('backend', 'You have new comment!') ?>
    </h3>

    <div class="timeline-body">
        <?php echo Yii::t('backend', 'New comment from ({user}) was added at {date}', [
            'user' => $model->data['name'],
            'date' => Yii::$app->formatter->asDatetime($model->data['date'])
        ]) ?>
    <hr>
        <?=$model->data['text']?>
    </div>

    <div class="timeline-footer">
        <?php echo \yii\helpers\Html::a(
            Yii::t('backend', 'View'),
            ['/comments/item/update', 'id' => $model->data['id']],
            [
                'class' => 'btn btn-success btn-sm',
                'data-pjax' => 0
            ]
        ) ?>
    </div>
</div>