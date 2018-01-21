<?php
namespace cmsgears\core\common\validators;

// Yii Imports
use yii\validators\Validator;

class CoreValidator {

	public static $builtInValidators = [
		// Yii - Excluded - date, datetime, time, each, exist, default, double, file, image, safe, unique
        'boolean' => 'cmsgears\core\common\validators\yii\BooleanValidator',
        'captcha' => 'cmsgears\core\common\validators\yii\CaptchaValidator',
        'compare' => 'cmsgears\core\common\validators\yii\CompareValidator',
        'email' => 'cmsgears\core\common\validators\yii\EmailValidator',
		'filter' => 'cmsgears\core\common\validators\yii\FilterValidator',
        'in' => 'cmsgears\core\common\validators\yii\RangeValidator',
        'integer' => [
            'class' => 'cmsgears\core\common\validators\yii\NumberValidator',
            'integerOnly' => true,
        ],
        'match' => 'cmsgears\core\common\validators\yii\RegularExpressionValidator',
        'number' => 'cmsgears\core\common\validators\yii\NumberValidator',
        'required' => 'cmsgears\core\common\validators\yii\RequiredValidator',
        'string' => 'cmsgears\core\common\validators\yii\StringValidator',
        'trim' => [
            'class' => 'cmsgears\core\common\validators\yii\FilterValidator',
            'filter' => 'trim',
            'skipOnArray' => true,
        ],
        'url' => 'cmsgears\core\common\validators\yii\UrlValidator',
        'ip' => 'cmsgears\core\common\validators\yii\IpValidator',
		// CMG
		'alphanumspace' => 'cmsgears\core\common\validators\AlphaNumWithSpaceValidator',
		'alphanumspaceu' => 'cmsgears\core\common\validators\AlphaNumWithSpaceUndescoreValidator',
		'alphanumpun' => 'cmsgears\core\common\validators\AlphaNumWithPunctuationValidator',
		'alphanumhyphen' => 'cmsgears\core\common\validators\AlphaNumWithHyphenValidator',
		'alphanumhyphenspace' => 'cmsgears\core\common\validators\AlphaNumWithHyphenSpaceValidator',
		'alphanumdotu' => 'cmsgears\core\common\validators\AlphaNumWithDotUnderscoreValidator',
		'alphanumu' => 'cmsgears\core\common\validators\AlphaNumWithUnderscoreValidator',
		'phone' => 'cmsgears\core\common\validators\PhoneValidator',
		'password' => 'cmsgears\core\common\validators\PasswordValidator',
		'compareDate' => 'cmsgears\core\common\validators\CompareDateValidator',
		'emptyArray' => 'cmsgears\core\common\validators\EmptyArrayValidator',
		'sanitize' => 'cmsgears\core\common\validators\SanitizeFilter'
	];

	public static function initValidators() {

		Validator::$builtInValidators = array_merge( Validator::$builtInValidators, self::$builtInValidators );
	}
}
