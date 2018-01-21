<?php
namespace cmsgears\core\common\validators\yii;

class FilterValidator extends \yii\validators\FilterValidator {

	public function clientValidateAttribute( $model, $attribute, $view ) {

		if( $this->filter !== 'trim' ) {

			return null;
        }

        ValidationAsset::register( $view );

        $options = $this->getClientOptions($model, $attribute);

        return 'value = yii.validation.trim($form, attribute, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
    }
}
