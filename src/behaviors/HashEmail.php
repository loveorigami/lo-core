<?php
namespace lo\core\behaviors;

use yii\db\ActiveRecord;
use yii\validators\Validator;

/**
 * Class HashEmail
 * Поведение для генерации md5 хеша
 * @package lo\core\behaviors
 * @property \lo\core\db\ActiveRecord $owner
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class HashEmail extends HashText
{
    /**
     * @var string
     */
    public $hashAttribute = 'hash';

    /**
     * @var string|array
     */
    public $attribute = 'email';

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => [$this, "beforeValidate"],
        ];
    }

    /**
     * @param $event
     */
    public function beforeValidate($event)
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $model->{$this->hashAttribute} = $this->getHash();

        $validator = Validator::createValidator('unique', $model, $this->hashAttribute);
        $validator->validateAttribute($model, $this->hashAttribute);
    }

    /**
     * generate md5 hash
     * @return string
     */
    private function getHash()
    {
        $owner = $this->owner;
        $str = $owner->{$this->attribute};

        $str = md5($str);

        return $str;
    }
}