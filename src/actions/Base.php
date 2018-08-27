<?php

namespace lo\core\actions;

use lo\core\traits\AccessRouteTrait;
use lo\core\traits\ActionTrait;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\Response;

/**
 * Class Base
 * Базовый класс для CRUD действий
 *
 * @package lo\core\actions
 */
class Base extends Action
{
    protected const FLASH_ERROR = 'error';
    protected const FLASH_SUCCESS = 'success';

    use AccessRouteTrait;
    use ActionTrait;

    /**
     * Рендеринг представления
     *
     * @param string $view   путь к представлению
     * @param array  $params массив параметров передаваемых в представление
     * @return string
     */
    public function render($view, array $params = []): string
    {
        $params = array_merge($params, $this->viewParams);

        if (Yii::$app->request->isAjax) {
            return $this->controller->renderAjax($view, $params);
        }

        return $this->controller->render($view, $params);
    }

    /**
     * Рендеринг представления без layout
     *
     * @param string $view   путь к представлению
     * @param array  $params массив параметров передаваемых в представление
     * @return string
     */
    public function renderPartial($view, array $params = []): string
    {
        $params = array_merge($params, $this->viewParams);

        return $this->controller->renderAjax($view, $params);
    }

    /**
     * Возвращание на предыдущую страницу
     */
    public function goBack(): Response
    {
        $returnUrl = Yii::$app->request->referrer;
        if (empty($returnUrl)) {
            $returnUrl = $this->defaultRedirectUrl;
        }

        return $this->controller->redirect($returnUrl);
    }

    /**
     * @param ActiveRecord $model
     * @return string
     */
    protected function getPk(ActiveRecord $model): string
    {
        if (\is_array($model->primaryKey)) {
            return implode(', ', $model->primaryKey);
        }

        return (string)$model->primaryKey;
    }

}
