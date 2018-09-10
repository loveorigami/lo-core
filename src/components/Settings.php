<?php

namespace lo\core\components;

use yii\base\Component;
use yii\caching\Cache;
use yii\helpers\ArrayHelper;
use Yii;
use lo\core\modules\settings\models\KeyStorageItem;

/**
 * Class Setings
 *
 * @package lo\core\components\Setings
 */
class Settings extends Component implements SettingsInterface
{
    /**
     * @var Cache|string the cache object or the application component ID of the cache object.
     * Settings will be cached through this cache object, if it is available.
     *
     * After the Settings object is created, if you want to change this property,
     * you should only assign it with a cache object.
     * Set this property to null if you do not want to cache the settings.
     */
    public $cache = 'cache';

    /**
     * @var string
     */
    public $cachePrefix = '_keyStorage';

    /**
     * @var int
     */
    public $cachingDuration = 60;

    /**
     * @var string
     */
    public $modelClass = KeyStorageItem::class;

    /**
     * @var array Runtime values cache
     */
    private $values = [];

    /**
     * Initialize the component
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (\is_string($this->cache)) {
            $this->cache = Yii::$app->get($this->cache);
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value): void
    {
        $model = $this->getModel($key);
        if (!$model) {
            $model = new $this->modelClass;
            $model->key = $key;
        }
        $model->value = $value;
        if ($model->save(false)) {
            $this->values[$key] = $value;
            $this->cache->set($this->getCacheKey($key), $value, $this->cachingDuration);
        }
    }

    /**
     * @param array $values
     */
    public function setAll(array $values)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param          $key
     * @param null     $default
     * @param bool     $cache
     * @param int|bool $cachingDuration
     * @return mixed|null
     */
    public function get($key, $default = null, $cache = true, $cachingDuration = false): ?string
    {
        if ($cache) {
            $cacheKey = $this->getCacheKey($key);
            $value = ArrayHelper::getValue($this->values, $key, false) ?: $this->cache->get($cacheKey);
            if ($value === false) {
                if ($model = $this->getModel($key)) {
                    $value = $model->value;
                    $this->values[$key] = $value;
                    $this->cache->set(
                        $cacheKey,
                        $value,
                        $cachingDuration === false ? $this->cachingDuration : $cachingDuration
                    );
                } else {
                    $value = $default;
                }
            }
        } else {
            $model = $this->getModel($key);
            $value = $model ? $model->value : $default;
        }

        return $value;
    }

    /**
     * @param array $keys
     * @return array
     */
    public function getAll(array $keys): array
    {
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->get($key);
        }

        return $values;
    }

    /**
     * @param      $key
     * @param bool $cache
     * @return bool
     */
    public function has($key, $cache = true): bool
    {
        return $this->get($key, null, $cache) !== null;
    }

    /**
     * @param array $keys
     * @return bool
     */
    public function hasAll(array $keys): bool
    {
        foreach ($keys as $key) {
            if (!$this->has($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $key
     * @return bool
     */
    public function remove($key): bool
    {
        unset($this->values[$key]);

        return \call_user_func($this->modelClass . '::deleteAll', ['key' => $key]);
    }

    /**
     * @param array $keys
     */
    public function removeAll(array $keys)
    {
        foreach ($keys as $key) {
            $this->remove($key);
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function getModel($key)
    {
        $query = \call_user_func($this->modelClass . '::find');

        return $query->where(['key' => $key])->one();
    }

    /**
     * @param $key
     * @return array
     */
    protected function getCacheKey($key): array
    {
        return [
            __CLASS__,
            $this->cachePrefix,
            $key,
        ];
    }

}
