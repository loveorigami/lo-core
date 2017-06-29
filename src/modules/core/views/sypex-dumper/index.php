<?php
/* @var $this yii\web\View */
$this->title = Yii::t('backend', 'Sypex Dumper')
?>

<div class="row">
    <div class="col-md-12 text-center">
        <?php echo \lo\core\widgets\admin\SypexDumper::widget(); ?>
    </div>

    <div class="col-md-12">
        <?php echo \mihaildev\elfinder\ElFinder::widget([
            'controller'       => 'elfinder/sxd',
            'language'         => 'ru',
            'frameOptions' => ['style'=>'min-height: 500px; width: 100%;  border: 0;'],
            'path'       => 'backup',
        ]);
        ?>
    </div>
</div>