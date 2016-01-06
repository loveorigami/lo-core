<?php
namespace lo\core\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\validators\Validator;

/**
 * Class HashText
 * Поведение для генерации md5 хеша
 * @package lo\core\behaviors
 * @property \lo\core\db\ActiveRecord $owner
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class HashText extends Behavior
{

    /**
     * @var string
     */
    public $hashAttribute = 'hash';

    /**
     * @var string|array
     */
    public $attribute = 'text';

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => [$this, "beforeValidate"],
        ];
    }

    public function beforeValidate($event)
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $model->{$this->hashAttribute} = $this->getHash();

        $validator = Validator::createValidator('unique', $model, $this->hashAttribute);
        $validator->validateAttribute($model, $this->hashAttribute);

    }


    private function getHash()
    {
        $owner = $this->owner;
        $str = $owner->{$this->attribute};

        // Удаляем все слова меньше 3-х символов
        $str = htmlspecialchars_decode($str);
        $str = mb_strtolower($str, 'utf-8');
        $str = preg_replace("|\b[\d\w]{1,3}\b|us", "", $str);

        // Удаляем знаки припенания
        $pattern = "/[\w\s\d]+/u";
        preg_match_all($pattern, $str, $result);
        $str = implode('', $result[0]);

        // Удаляем лишние пробельные символы
        $str = preg_replace("/[\s]+/us", "", $str);
        $str = md5($str);

        return $str;

    }

}