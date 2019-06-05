<?php

namespace lo\core\behaviors;

use Ramsey\Uuid;
use Yii;
use yii\base;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db;
use yii\db\BaseActiveRecord;
use yii\di;
use yii\validators\UniqueValidator;

/**
 * Class UuidBehavior
 *
 * @package common\behaviors
 * @author  Lukyanov Andrey <loveorigami@mail.ru>
 */
class UuidBehavior extends AttributeBehavior
{
    /**
     * @var string название атрибута модели для записи uuid
     */
    public $uuidAttribute = 'uuid';

    /**
     * @var callable|string|null значение, содержащее uuid. Может быть заполнено в качестве анонимной функции
     * или быть null.
     * Если `null` будет в свойстве `$attribute` будет инициирована генерация uuid.
     * Анонимная функция может быть вызвана как
     *
     * ```php
     * function ($event)
     * {
     *     // return uuid
     * }
     * ```
     */
    public $value;

    /**
     * @var bool генерировать ли уникальный uuid.
     */
    public $ensureUnique = true;

    /**
     * @var array конфигурация уникального валидатора. Если будет не задан параметр 'class' - будет использован
     * [[UniqueValidator]] по умолчанию.
     * @see UniqueValidator
     */
    public $uniqueValidator = [];

    /**
     * Количество попыток получить уникальный uuid, до того, как будет получена ошибка валидации
     *
     * @var int
     */
    public $maxAttempts = 10;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [BaseActiveRecord::EVENT_BEFORE_VALIDATE => $this->uuidAttribute];
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue($event)
    {
        if (!$this->isNewUuidNeeded()) {
            return $this->owner->{$this->uuidAttribute};
        }

        $uuid = $this->generateUuid();

        return $this->ensureUnique ? $this->makeUnique($uuid) : $uuid;
    }

    /**
     * Проверка, нужно ли генерировать новое значение uuid
     *
     * @return bool
     */
    protected function isNewUuidNeeded(): bool
    {
        if (empty($this->owner->{$this->uuidAttribute})) {
            return true;
        }

        return false;
    }

    /**
     * Метод, вызываемый в [[getValue]] для генерации uuid.
     * Может быть перезаписан под свой алгоритм получения UUID.
     * Для получения уникального значения, генератор поставлен в зависимость от метки времени.
     *
     * @see https://github.com/ramsey/uuid
     *
     * @return string the conversion result.
     */
    protected function generateUuid(): string
    {
        // Generate a version 5 (name-based and hashed with SHA1) UUID object
        $uuid5 = Uuid::uuid5(Uuid::NAMESPACE_DNS, time());

        return $uuid5->toString();
    }

    /**
     * Метод, вызываемый в [[getValue]] при [[ensureUnique]] = true для генерации уникального UUID.
     * Использует метод [[generateUuid]] для пвоторной генерации UUID.
     *
     * @param $uuid
     * @return string unique $uuid
     * @throws InvalidConfigException
     * @see   getValue
     */
    protected function makeUnique($uuid): string
    {
        $uniqueUuid = $uuid;
        $attempts = 0;
        while (!$this->validateUuid($uuid) && $attempts <= $this->maxAttempts) {
            $attempts++;
            $uniqueUuid = $this->generateUuid();
        }

        return $uniqueUuid;
    }

    /**
     * Проверка, является ли UUID действительно уникальным.
     *
     * @param $uuid
     * @return bool whether slug is unique.
     * @throws InvalidConfigException
     */
    protected function validateUuid($uuid): bool
    {
        /* @var $validator UniqueValidator */
        /* @var $model BaseActiveRecord */
        $validator = Yii::createObject(\array_merge(
            [
                'class' => UniqueValidator::class,
            ],
            $this->uniqueValidator
        ));

        $model = clone $this->owner;
        $model->clearErrors();
        $model->{$this->uuidAttribute} = $uuid;

        $validator->validateAttribute($model, $this->uuidAttribute);

        return !$model->hasErrors();
    }
}
