<?php

namespace lo\core\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\HtmlPurifier;

/**
 * Class PurifierBehavior
 * HTMLPurifier behavior.
 *
 * Usage:
 * ```
 * ...
 * 'purifierBehavior' => [
 *     'class' => PurifierBehavior::className(),
 *     'attributes' => [
 *             'snippet',
 *             'content' => [
 *                 'HTML.AllowedElements' => '',
 *                 'AutoFormat.RemoveEmpty' => true
 *             ]
 *         ]
 * ]
 * ...
 * ```
 *
 * @property array $attributes Attributes array with settings
 * @property array $textAttributes Text attributes array with settings
 * @property array $purifierOptions Purifier settings
 */
class PurifierBehavior extends Behavior
{
    /**
     * @var array Attributes array
     */
    public $attributes = [];

    /**
     * @var array Purifier settings
     */
    public $purifierSettings = [
        'AutoFormat.RemoveEmpty' => true,
        'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
        'AutoFormat.Linkify' => true,
        'HTML.Nofollow' => true
    ];

    /**
     * @var HtmlPurifier
     */
    private $purifier;

    public function __construct(HtmlPurifier $purifier, array $config = [])
    {
        $this->purifier = $purifier;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => "beforeValidate",
        ];
    }

    public function beforeValidate()
    {
        if (!empty($this->attributes)) {
            $this->purify();
        }
    }

    /**
     * Purify attributes
     */
    public function purify()
    {
        foreach ($this->attributes as $attribute => $config) {
            if (is_array($config)) {
                $settings = $config;
            } else {
                $attribute = $config;
                $settings = $this->purifierSettings;
            }
            $this->owner->$attribute = $this->purifier->process($this->owner->$attribute, $settings);
        }
    }
}