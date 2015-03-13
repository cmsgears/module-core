<?php
namespace cmsgears\modules\core\common\validators;

// Yii Imports
use yii\validators\Validator;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
use cmsgears\modules\core\common\utilities\MessageUtil;

class PhoneValidator extends Validator {

	private static $regex	= "/^(\\+|[0-9 -])([0-9 -]+[0-9])$/";

    public function validateAttribute($model, $attribute) {

        if ( !preg_match( self::$regex, $model->$attribute ) ) {

            $this->addError( $model, $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_PHONE ) );
        }
    }
}

?>