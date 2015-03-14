<?php
namespace cmsgears\core\common\validators;

// Yii Imports
use yii\validators\Validator;

use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\utilities\MessageUtil;

class AlphaNumWithPunctuationValidator extends Validator {

	private static $regex	= "/^[a-zA-Z0-9?!.,\"'\/][a-zA-Z0-9?!.,\"'\/ ]+[a-zA-Z0-9?!.,\"'\/]$/";

    public function validateAttribute($model, $attribute) {

        if ( !preg_match( self::$regex, $model->$attribute ) ) {

            $this->addError( $model, $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_AN_PUN ) );
        }
    }
}

?>