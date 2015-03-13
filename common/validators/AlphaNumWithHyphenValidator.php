<?php
namespace cmsgears\modules\core\common\validators;

// Yii Imports
use yii\validators\Validator;

use cmsgears\modules\core\common\config\CoreGlobal;
use cmsgears\modules\core\common\utilities\MessageUtil;

class AlphaNumWithHyphenValidator extends Validator {

	private static $regex	= "/^[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]$/";

    public function validateAttribute($model, $attribute) {

        if ( !preg_match( self::$regex, $model->$attribute ) ) {

            $this->addError( $model, $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_AN_HYPHEN ) );
        }
    }
}

?>