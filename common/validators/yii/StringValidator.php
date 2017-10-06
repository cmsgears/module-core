<?php
namespace cmsgears\core\common\validators\yii;

class StringValidator extends \yii\validators\StringValidator {

    public function clientValidateAttribute( $model, $attribute, $view ) {

        ValidationAsset::register( $view );

		$options = $this->getClientOptions( $model, $attribute );

        return 'yii.validation.string(value, messages, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
    }
}
