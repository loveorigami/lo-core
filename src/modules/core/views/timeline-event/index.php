<?php
/**
 * @var $this \yii\web\View
 * @var $model lo\core\modules\core\models\TimelineEvent
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\base\InvalidParamException;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

$this->title = Yii::t('backend', 'Application timeline');
?>
<?php Pjax::begin() ?>
    <div class="row" style="background-color: #ecf0f5">
        <div class="col-md-12">
            <?php if ($dataProvider->count > 0): ?>
                <ul class="timeline">
                    <?php foreach ($dataProvider->getModels() as $model): ?>
                        <?php if (!isset($date) || $date != Yii::$app->formatter->asDate($model->created_at)): ?>
                            <!-- timeline time label -->
                            <li class="time-label">
                            <span class="bg-blue">
                                <?php echo Yii::$app->formatter->asDate($model->created_at) ?>
                            </span>
                            </li>
                            <?php $date = Yii::$app->formatter->asDate($model->created_at) ?>
                        <?php endif; ?>
                        <li>
                            <?php
                            try {
                                $viewFile = sprintf('%s/%s', $model->category, $model->event);
                                $view = $this->render($viewFile, ['model' => $model]);
                            } catch (InvalidParamException $e) {
                                $view = $this->render('_item', ['model' => $model]);
                            }

                            $btnDel = Html::a(
                                Yii::t('backend', 'Delete'),
                                ['delete', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-danger btn-xs',
                                    'data-method' => 'post'
                                ]
                            );

                            echo str_replace('{delete}', $btnDel, $view);

                            ?>
                        </li>
                    <?php endforeach; ?>
                    <li><i class="fa fa-clock-o"></i></li>
                </ul>
            <?php else: ?>
                <?php echo Yii::t('backend', 'No events found') ?>
            <?php endif; ?>
        </div>
        <div class="col-md-12 form-inline" style="margin-top:20px">
            <?php echo LinkPager::widget([
                'pagination' => $dataProvider->pagination,
                'options' => ['class' => 'pagination no-margin pull-left']
            ]) ?>
        </div>
    </div>
<?php Pjax::end() ?>