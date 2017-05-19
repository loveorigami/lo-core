<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \lo\core\db\ActiveRecord $model модель
 * @var \yii\web\View $this
 * @var string $id идентификатор виджета
 * @var array $models валидируемые модели
 * @var array $formOptions параметры \yii\widgets\ActiveForm
 */

$perm = $model->getPermission();
$meta = $model->getMetaFields();

$tabId = "$id-tabs";

$this->registerJs("
    $('#{$tabId} a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
");
?>

<?php $form = ActiveForm::begin($formOptions); ?>

    <div class="pull-right">
        <?= Html::hiddenInput('apply', 0) ?>
        <?= Html::hiddenInput('tab', Yii::$app->request->post('tab')) ?>
        <?php $returnUrl = Yii::$app->request->get('returnUrl', Yii::$app->request->post('returnUrl', Yii::$app->request->referrer)); ?>
        <?= Html::hiddenInput('returnUrl', $returnUrl) ?>

        <?= Html::submitButton(Yii::t('core', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton(Yii::t('core', 'Apply'), ['class' => 'btn btn-primary form-apply']) ?>
        <?= Html::button(Yii::t('core', 'Cancel'), ['class' => 'btn btn-default form-cancel']) ?>

    </div>

<?php echo $form->errorSummary($model); ?>

    <ul id="<?= $tabId ?>" class="nav nav-tabs">
        <?php
        $i = 0;
        foreach ($meta->tabs() AS $key => $title):?>
            <li <?php if ($i == 0): ?>class="active"<?php endif; ?>><a href="#<?= $key ?>"><?= $title ?></a></li>
            <?php
            $i++;
        endforeach; ?>
    </ul>

    <div class="tab-content">
        <?php
        $i = 0;

        foreach ($meta->tabs() AS $key => $title):
            $tpl = [];
            $html = '';
            /** @var lo\core\widgets\admin\Form $widget */
            $widget = $this->context;
            $template = $widget->getTplFile($key);
            ?>

            <div class="<?php if ($i == 0): ?>active <?php endif; ?>tab-pane" id="<?= $key ?>">

                <?php

                foreach ($meta->getFieldsByTab($key) AS $field) {
                    if ($perm AND $perm->isAttributeForbidden($field->attr)) {
                        if (strpos($template, '{' . $field->attr . '}') !== false) {
                            $tpl['search'][] = '/{' . $field->attr . '}/';
                            $tpl['replace'][] = '';
                        }
                        continue;
                    }

                    if ($template && strpos($template, '{' . $field->attr . '}') !== false) {
                        $tpl['search'][] = '/{' . $field->attr . '}/';
                        $tpl['replace'][] = $field->getWrappedForm($form);
                    } else {
                        $html .= $field->getWrappedForm($form);

                    }
                }

                if (!empty($tpl)) {
                    echo preg_replace($tpl['search'], $tpl['replace'], $template);
                }

                echo $html;

                ?>

            </div>

            <?php $i++; endforeach; ?>
    </div>


<?php
$this->registerJs("

    (function(){

        $('.form-apply').on('click', function(e){ $(\"input[name='apply']\").val(1);});
	    $('.form-cancel').on('click', function(e){ e.preventDefault(); window.location.href='$returnUrl'; });
	    
	    $('#$tabId a').on('show.bs.tab', function(e){ 
	        localStorage.setItem('lastTab', $(this).attr('href'));
	    });
       
        // go to the latest tab, if it exists:
        var lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $('#$tabId a[href=\"' + lastTab + '\"]').tab('show');
        }

    })();

");
?>

<?php ActiveForm::end(); ?>