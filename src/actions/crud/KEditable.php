<?php

namespace lo\core\actions\crud;

use kartik\grid\EditableColumnAction;
use Yii;
use yii\web\Response;

/**
 * Class XEditable
 * Класс действия обновления модели через расширение XEditable
 * @package lo\core\actions\crud
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class KEditable extends EditableColumnAction
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $out = $this->validateEditable();
        return Yii::createObject(['class' => Response::class, 'format' => Response::FORMAT_JSON, 'data' => $out]);
    }
}