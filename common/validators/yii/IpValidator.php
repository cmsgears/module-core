<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\validators\yii;

// Yii Imports
use yii\helpers\Json;

class IpValidator extends \yii\validators\IpValidator {

	public function clientValidateAttribute( $model, $attribute, $view ) {

		ValidationAsset::register( $view );

		$options = $this->getClientOptions( $model, $attribute );

		return 'yii.validation.ip(value, messages, ' . Json::htmlEncode($options) . ');';
    }

}
