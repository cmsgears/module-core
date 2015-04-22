<?php
namespace cmsgears\core\common\validators;

// Yii Imports
use \Yii;
use yii\validators\Validator;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class PhoneValidator extends Validator {

	private static $regex	= "/^(\\+|[0-9 -])([0-9 -]+[0-9])$/";

    public function validateAttribute($model, $attribute) {

        if ( !preg_match( self::$regex, $model->$attribute ) ) {

            $this->addError( $model, $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_PHONE ) );
        }
    }
}

?>