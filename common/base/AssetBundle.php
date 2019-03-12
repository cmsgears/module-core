<?php
namespace cmsgears\core\common\base;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;

class AssetBundle extends \yii\web\AssetBundle {

    public function init() {

		parent::init();

        $this->baseUrl = YII_ENV_PROD ? CoreProperties::getInstance()->getResourceUrl() : $this->baseUrl;
    }

}
