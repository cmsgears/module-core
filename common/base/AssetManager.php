<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\base;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;

class AssetManager extends \yii\web\AssetManager {

    public function init() {

		parent::init();

        $this->baseUrl = YII_ENV_PROD ? CoreProperties::getInstance()->getResourceUrl() . '/assets' : $this->baseUrl;
    }

}
