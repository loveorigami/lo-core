<?php
/**
 * @var \lo\core\modules\core\models\TimelineEvent $model
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
            ['/user/admin/update', 'id' => $model->getData('id')],
            ['data-pjax' => 0]
        ) ?>
    </h3>
    <div class="timeline-body">
        <?php echo Yii::t('backend', 'New user {identity} was registered at {created_at}', [
            'identity' => '<span class="label bg-purple">' . $model->getData('username') . ': ' . $model->getData('role') . '</span>',
            'created_at' => Yii::$app->formatter->asDatetime($model->getData('created_at'))
        ]) ?>
    </div>

    <div class="timeline-footer">
        <?= Html::a(
            Yii::t('backend', 'View'),
            ['/user/admin/update', 'id' => $model->getData('id')],
            [
                'class' => 'btn btn-success btn-xs',
                'data-pjax' => 0
            ]
        ) ?>
        {delete}
    </div>
</div>

