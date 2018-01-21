<?php
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
