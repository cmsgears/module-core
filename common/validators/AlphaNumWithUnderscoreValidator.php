<?php
namespace cmsgears\core\common\validators;

// Yii Imports
use \Yii;
use yii\validators\Validator;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class AlphaNumWithUnderscoreValidator extends Validator {

	private static $regex	= "/^[a-zA-Z_0-9]+$/";

    public function validateAttribute($model, $attribute) {

        if ( !preg_match( self::$regex, $model->$attribute ) ) {

            $this->addError( $model, $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_AN_U ) );
        }
    }
}

?>