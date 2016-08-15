<?php
namespace lo\core\actions;

use lo\core\rbac\AccessRouteTrait;
use lo\core\traits\ActionTrait;
use Yii;
use yii\base\Action;

/**
 * Class Base
 * Базовый класс для CRUD действий
 * @package lo\core\actions
 */
class Base extends Action
{
    use AccessRouteTrait;
    use ActionTrait;

    /**
     * Рендеринг представления
     * @param string $view путь к представлению
     * @param array $params массив параметров передаваемых в представление
     * @return string
     */
    public function render($view, $params = [])
    {
        $params = array_merge($params, $this->viewParams);

        if (Yii::$app->request->isAjax) {
            return $this->controller->renderAjax($view,$params);
        }

        return $this->controller->render($view, $params);
    }

    /**
     * Рендеринг представления без layout
     * @param string $view путь к представлению
     * @param array $params массив параметров передаваемых в представление
     * @return string
     */
    public function renderPartial($view, $params = [])
    {
        $params = array_merge($params, $this->viewParams);
        return $this->controller->renderAjax($view, $params);
    }

    /**
     * Возвращание на предыдущую страницу
     * @return \yii\web\Response
     */
    public function goBack()
    {
        $returnUrl = Yii::$app->request->referrer;
        if (empty($returnUrl))
            $returnUrl = $this->defaultRedirectUrl;
        return $this->controller->redirect($returnUrl);
    }
}