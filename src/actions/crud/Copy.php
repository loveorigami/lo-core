<?php

namespace lo\core\actions\crud;

use lo\core\actions\Base;
use lo\core\db\ActiveRecord;
use lo\core\helpers\PkHelper;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class Copy
 * @package lo\core\actions\crud
 * ```php
 *  'copy' => [
 *      'class' => crud\Copy::class,
 *      'modelClass' => $class,
 *      'afterCopy' => function ($model, $modelCopy) {
 *          foreach ($model->tags as $tag) {
 *              $tagCopy = new Tag();
 *              $tagCopy->setAttributes($tag->attributes);
 *              $tagCopy->save();
 *              $modelCopy->link('tags', $tagCopy);
 *          }
 *      }
 * }],
 *
 * ```
 */
class Copy extends Base
{
    public $afterCopy = true;
    public $copyError = 'error';

    /**
     * Запуск действия копирования модели
     * @param integer $id идентификатор модели
     * @throws ForbiddenHttpException
     * @return Response
     */
    public function run($id)
    {
        if (Yii::$app->request->isPost) {
            $pk = PkHelper::decode($id);
            /** @var ActiveRecord $model */
            $model = $this->findModel($pk);

            $this->canAction($model);

            /** @var ActiveRecord $modelCopy */
            $modelCopy = new $this->modelClass();
            $modelCopy->setAttributes($model->attributes);
            $modelCopy->save();

            if ($this->afterCopy instanceof \Closure) {
                try {
                    call_user_func($this->afterCopy, $model, $modelCopy);
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash(self::FLASH_ERROR, $this->copyError);
                    \Yii::error("An error occurred in mailer: {$e->getMessage()}, code: {$e->getCode()}");
                }
            }
        }

        if (!Yii::$app->request->isAjax) {
            return $this->goBack();
        }

        return null;
    }

}