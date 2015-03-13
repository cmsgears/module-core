<?php
namespace cmsgears\core\common\utilities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class MessageUtil {

	// Variables ---------------------------------------------------

	// Static Variables --------------------

	private static $errorsDb = [
		// Errors - Generic
		CoreGlobal::ERROR_REQUEST => 'Your request was not processed. Please correct the highlighted errors and submit again.',
		CoreGlobal::ERROR_NOT_FOUND => 'The requested resource does not exist.',
		CoreGlobal::ERROR_NOT_ALLOWED => 'You are not allowed to perform this action.',
		CoreGlobal::ERROR_EXIST => 'An entry with the same name already exist. Please provide a different name.',
		CoreGlobal::ERROR_SELECT => 'Please choose a valid value.',
		CoreGlobal::ERROR_URL_ENTITY => 'An entry with the same url already exist.',
		// Errors - Validators
		CoreGlobal::ERROR_AN_SPACE => 'Please provide a valid value without any special character.',
		CoreGlobal::ERROR_AN_PUN => "Please provide valid value having alphanumeric and (? ! . , \" ').",
		CoreGlobal::ERROR_AN_HYPHEN => 'Please provide valid value having alphanumeric and hyphen.',
		CoreGlobal::ERROR_AN_HYPHEN_SPACE => 'Please provide valid value having alphanumeric, space and hyphen.',
		CoreGlobal::ERROR_AN_DOT_U => 'Please provide valid value having alphanumeric, dot(.) or underscore(_) characters.',
		CoreGlobal::ERROR_PHONE => 'Please provide a valid number having numeric digits or hyphen(-), space or plus(+) at beginning.',
		// Errors - Create/Register User
		CoreGlobal::ERROR_USER_EXIST => 'A user already exist with the same Email.',
		CoreGlobal::ERROR_USERNAME_EXIST => 'A user already exist with the same username.',
		CoreGlobal::ERROR_PASSWORD => 'Please provide a valid password having at least 5 characters.',
		CoreGlobal::ERROR_TERMS => 'Please accept our terms and conditions to continue.',
		CoreGlobal::ERROR_USER_NOT_EXIST => 'A user with the provided email is not registered.',	
		CoreGlobal::ERROR_USER_VERIFICATION	=> 'The account with this email is not confirmed yet. Please follow the mail sent while registration or try to reset password.',
		CoreGlobal::ERROR_BLOCKED => 'The account is blocked by Admin. Please contact Admin to re-activate it.',
		CoreGlobal::ERROR_LOGIN_FAILED => 'The provided username and password does not match.',
		// Errors - User Account
		CoreGlobal::ERROR_ACCOUNT_CONFIRM => 'Either your account does not exist or the confirmation link is not valid. Please try to reset your password.',
		CoreGlobal::ERROR_PASSWORD_RESET => 'Either your account does not exist or the reset link is not valid. Please try to reset your password.',
		// Messages
		CoreGlobal::MESSAGE_REQUEST => 'Your request was processed successfully.',
		// Messages - Create/Register User
		CoreGlobal::MESSAGE_REGISTER => 'Thanks for creating your account. A confirmation email having activation link was sent to the given email address.',
		CoreGlobal::MESSAGE_ACCOUNT_CONFIRM => 'Thanks for confirming your account. Please login to continue with us.',
		CoreGlobal::MESSAGE_FORGOT_PASSWORD => 'A confirmation email having password reset link was sent to the given email address.',
		CoreGlobal::MESSAGE_RESET_PASSWORD => 'Your password reset request was processed successfully. Please login to continue with us.'
	];

	public static function getMessage( $messageKey ) {

		return self::$errorsDb[ $messageKey ];
	}
}

?>