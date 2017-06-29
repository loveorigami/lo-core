<?php
namespace lo\core\modules\core\models;

use lo\core\behaviors\MatchSuitable;
use lo\core\components\match\Match;
use lo\core\db\ActiveRecord;
use Yii;

/**
 * Class Template
 * @package lo\modules\core\models
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 * @property integer $id
 * @property integer $status
 * @property integer $author_id
 * @property integer $updater_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $name
 * @property string $text
 * @property string $layout
 * @property string $cond
 * @property int $cond_type
 * @property string $pos
 *
 * @mixin MatchSuitable
 */
class Template extends ActiveRecord
{
    /**
     * Возвращает массив условий подклбчений шаблона
     * @return array
     */
    public static function getConds()
    {
        return [
            Match::COND_NO => Yii::t("backend", "No condition"),
            Match::COND_URL => Yii::t("backend", "Url condition"),
            Match::COND_PHP => Yii::t("backend", "Php condition"),
            Match::COND_ROUTE => Yii::t("backend", "Route condition"),
        ];
    }

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return "{{%core__templates}}";
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $arr = parent::behaviors();
        $arr["matchSuitable"] = MatchSuitable::class;
        return $arr;
    }

    /**
     * @inheritdoc
     */
    public function metaClass()
    {
        return TemplateMeta::class;
    }

}