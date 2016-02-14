<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin([
    'action' => $action,
    'method' => 'get',
    'fieldConfig' => [
        'template' => "{input}",
        'options' => [
            'tag'=>'div'
        ]
    ]
]);

echo $form->field($searchModel, 'text', [
    'template' => '<div class="input-group">{input}<span class="input-group-btn">'.
        '<button type="submit" tabindex="-1" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button></span></div>',
]); ?>


<?php $form->end(); ?>

