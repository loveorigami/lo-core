<?php

namespace lo\core\db\fields;

use Yii;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class ManyManySortField
 * Поле для связи ManyMany с возможностью сортировки привязанных элементов
 * @package lo\core\db\fields
 * @author Churkin Anton <webadmin87@gmail.com>
 */
class ManyManySortField extends ManyManyField
{

   public $inputClass = '\lo\core\inputs\TagsSortedInput';

    /**
     * @var bool отображать в фильтре грида
     */
    public $showInFilter = true;

} 