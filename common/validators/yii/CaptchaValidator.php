<?php
namespace cmsgears\core\common\validators\yii;

class CaptchaValidator extends \yii\captcha\CaptchaValidator {

    public function clientValidateAttribute( $model, $attribute, $view ) {

        ValidationAsset::register( $view );

		$options = $this->getClientOptions( $model, $attribute );

        return 'yii.validation.captcha(value, messages, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
    }
}
