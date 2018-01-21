<?php
namespace cmsgears\core\common\validators\yii;

// Yii Imports
use yii\helpers\Json;

class UrlValidator extends \yii\validators\UrlValidator {

    public function clientValidateAttribute( $model, $attribute, $view ) {

		ValidationAsset::register( $view );

		if( $this->enableIDN ) {

			PunycodeAsset::register( $view );
		}

		$options = $this->getClientOptions($model, $attribute);

		return 'yii.validation.url(value, messages, ' . Json::htmlEncode($options) . ');';
    }
}
