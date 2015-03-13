<?php
namespace cmsgears\modules\core\common\validators;

// Yii Imports
use yii\validators\Validator;

class CoreValidator {

    public static $builtInValidators = [
        'alphanumspace' => 'cmsgears\modules\core\common\validators\AlphaNumWithSpaceValidator',
        'alphanumpun' => 'cmsgears\modules\core\common\validators\AlphaNumWithPunctuationValidator',
        'alphanumhyphen' => 'cmsgears\modules\core\common\validators\AlphaNumWithHyphenValidator',
        'alphanumhyphenspace' => 'cmsgears\modules\core\common\validators\AlphaNumWithHyphenSpaceValidator',
        'alphanumdotu' => 'cmsgears\modules\core\common\validators\AlphaNumWithDotUnderscoreValidator',
        'phone' => 'cmsgears\modules\core\common\validators\PhoneValidator',
        'password' => 'cmsgears\modules\core\common\validators\PasswordValidator'
        
    ];

	public static function initValidators() {
		
		Validator::$builtInValidators = array_merge( Validator::$builtInValidators, self::$builtInValidators );
	}
}

?>