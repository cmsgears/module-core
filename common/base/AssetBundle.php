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

class AssetBundle extends \yii\web\AssetBundle {

    public function init() {

		parent::init();

        $this->baseUrl = YII_ENV_PROD ? CoreProperties::getInstance()->getResourceUrl() : $this->baseUrl;
    }

}
