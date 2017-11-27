<?php
/**
 * @var $model
 */
use yii\helpers\Html;

?>
<i class="fa fa-user bg-blue"></i>
<div class="timeline-item">
    <span class="time">
        <i class="fa fa-clock-o"></i>
        <?php echo Yii::$app->formatter->asRelativeTime($model->created_at) ?>
    </span>
    <h3 class="timeline-header">
        <?= Html::a(
            Yii::t('backend', 'You have new user!'),
            ['/user/admin/update', 'id' => $model->data['id']],
            ['data-pjax' => 0]
        ) ?>
    </h3>
    <div class="timeline-body">
        <?php echo Yii::t('backend', 'New user {identity} was registered at {created_at}', [
            'identity' => '<span class="label bg-purple">' . $model->data['username'] . ': ' . $model->data['role'] . '</span>',
            'created_at' => Yii::$app->formatter->asDatetime($model->data['created_at'])
        ]) ?>
    </div>

    <div class="timeline-footer">
        <?= Html::a(
            Yii::t('backend', 'View'),
            ['/user/admin/update', 'id' => $model->data['id']],
            [
                'class' => 'btn btn-success btn-xs',
                'data-pjax' => 0
            ]
        ) ?>
        {delete}
    </div>
</div>

