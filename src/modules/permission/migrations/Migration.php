<?php
namespace lo\core\modules\permission\migrations;

use lo\core\db\Migration as BaseMigration;

class Migration extends BaseMigration
{
    public $tableGroup = "user";

    const TBL_CONSTRAINT = 'constraint';

    /**
     * @var string|\yii\rbac\BaseManager
     */
    public $auth = 'authManager';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->auth = \Yii::$app->get('authManager');
    }
}