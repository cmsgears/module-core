<?php
namespace cmsgears\core\common\base;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;

class AssetManager extends \yii\web\AssetManager {

    public function init() {

		parent::init();

        $this->baseUrl = YII_ENV_PROD ? CoreProperties::getInstance()->getResourceUrl() . '/assets' : $this->baseUrl;
    }

}
