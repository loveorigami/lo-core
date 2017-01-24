<?php

namespace lo\core\helpers;

use Yii;
use yii\web\Controller;
use yii\web\View;

class ViewHelper
{
    /**
     * inspired by yii\base\View::findViewFile()
     *
     * Finds the view file based on the given view name.
     *
     * @param string $view the view name or the path alias of the view file. Please refer to [[render()]]
     * on how to specify this parameter.
     * @param object $context the context to be assigned to the view and can later be accessed via [[context]]
     * in the view. If the context implements [[ViewContextInterface]], it may also be used to locate
     * the view file corresponding to a relative view name.
     * @return string the view file path. Note that the file may not exist.
     * determine the corresponding view file.
     */
    static function find($view, $context = null)
    {
        if (strncmp($view, '@', 1) === 0) {
            // e.g. "@app/views/main"
            $file = Yii::getAlias($view);
        } elseif (strncmp($view, '//', 2) === 0) {
            // e.g. "//layouts/main"
            $file = Yii::$app->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
        } elseif (strncmp($view, '/', 1) === 0) {
            // e.g. "/site/index"
            if (Yii::$app->controller !== null) {
                $file = Yii::$app->controller->module->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
            } else {
                return NULL;
            }
        } elseif ($context instanceof Controller) {
            $file = $context->getViewPath() . DIRECTORY_SEPARATOR . $view;
        } elseif ($context instanceof View) {
            if (($currentViewFile = $context->getViewFile()) !== false) {
                $file = dirname($currentViewFile) . DIRECTORY_SEPARATOR . $view;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }
        $path = $file . '.' . Yii::$app->getView()->defaultExtension;
        if (Yii::$app->getView()->defaultExtension !== 'php' && !is_file($path)) {
            $path = $file . '.php';
        }
        return $path;
    }

    /**
     * check whether a view exist
     *
     * @param string $view
     * @param Controller|View $context
     * @return boolean
     */
    static function exist($view, $context = NULL)
    {
        $file = static::find($view, $context);
        return $file ? file_exists($file) : FALSE;
    }
}