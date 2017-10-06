<?php
namespace cmsgears\core\common\validators\yii;

// Yii Imports
use yii\helpers\Json;

class RegularExpressionValidator extends \yii\validators\RegularExpressionValidator {

    public function clientValidateAttribute( $model, $attribute, $view ) {

        ValidationAsset::register( $view );

        $options = $this->getClientOptions( $model, $attribute );

        return 'yii.validation.regularExpression(value, messages, ' . Json::htmlEncode($options) . ');';
    }
}
