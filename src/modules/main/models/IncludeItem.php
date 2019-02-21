<?php
namespace lo\core\modules\main\models;

use lo\core\db\ActiveRecord;

/**
 * Class IncludeItem
 * Модель включаемых областей
 * @property int $id
 * @property string $code
 * @property string $text
 * @property string $file
 * @package lo\modules\main\models
 */
class IncludeItem extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    /** @var IncludeItem[] массив включаемых областей */
    protected static $models;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return "{{%core__include_item}}";
    }

    /**
     * Возвращает включаемую область по символьному коду
     * @param string $code символьный код включаемой области
     * @return IncludeItem|null
     */
    public static function findByCode($code)
    {
        $models = static::findAllModels();

        /**@var IncludeItem $model */
        foreach ($models as $model) {
            if ($model->code == $code)
                return $model;
        }

        return null;
    }

    /**
     * Возвращает выборку всех активных включаемых областей.
     * Кешируются в статическом свойстве
     * @return IncludeItem[]
     */
    public static function findAllModels()
    {
        if (static::$models === null) {
            static::$models = static::find()->published()->all();
        }

        return static::$models;
    }

    /**
     * @inheritdoc
     */
    public function metaClass()
    {
        return IncludeItemMeta::class;
    }

}