<?php
namespace cmsgears\core\common\validators\yii;

// Yii Imports
use yii\helpers\Json;

class EmailValidator extends \yii\validators\EmailValidator {

    public function clientValidateAttribute( $model, $attribute, $view ) {

        ValidationAsset::register( $view );

		if( $this->enableIDN ) {

            PunycodeAsset::register( $view );
        }

        $options = $this->getClientOptions( $model, $attribute );

        return 'yii.validation.email(value, messages, ' . Json::htmlEncode($options) . ');';
    }
}
