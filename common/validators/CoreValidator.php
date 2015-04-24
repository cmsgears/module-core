<?php
namespace cmsgears\core\common\validators;

// Yii Imports
use yii\validators\Validator;

class CoreValidator {

    public static $builtInValidators = [
        'alphanumspace' => 'cmsgears\core\common\validators\AlphaNumWithSpaceValidator',
        'alphanumpun' => 'cmsgears\core\common\validators\AlphaNumWithPunctuationValidator',
        'alphanumhyphen' => 'cmsgears\core\common\validators\AlphaNumWithHyphenValidator',
        'alphanumhyphenspace' => 'cmsgears\core\common\validators\AlphaNumWithHyphenSpaceValidator',
        'alphanumdotu' => 'cmsgears\core\common\validators\AlphaNumWithDotUnderscoreValidator',
        'phone' => 'cmsgears\core\common\validators\PhoneValidator',
        'password' => 'cmsgears\core\common\validators\PasswordValidator'
        
    ];

	public static function initValidators() {
		
		Validator::$builtInValidators = array_merge( Validator::$builtInValidators, self::$builtInValidators );
	}
}

?>