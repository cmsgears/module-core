<?php
namespace cmsgears\core\common\validators\yii;

// Yii Imports
use yii\helpers\Json;

class NumberValidator extends \yii\validators\NumberValidator {

    public function clientValidateAttribute( $model, $attribute, $view ) {

        ValidationAsset::register( $view );

        $options = $this->getClientOptions( $model, $attribute );

        return 'yii.validation.number(value, messages, ' . Json::htmlEncode($options) . ');';
    }
}
