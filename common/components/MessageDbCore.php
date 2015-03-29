<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

class MessageDbCore extends Component {

	// Errors - Generic
	const ERROR_REQUEST				= "requestError";
	const ERROR_NOT_FOUND			= "notFoundError";
	const ERROR_NOT_ALLOWED			= "notAllowedError";
	const ERROR_EXIST				= "entryExistError";
	const ERROR_SELECT				= "selectError";
	const ERROR_URL_ENTITY 			= "urlEntityError";

	// Errors - Core Validators
	const ERROR_AN_SPACE			= "alphaNumSpaceError";
	const ERROR_AN_PUN				= "alphaNumPunError";
	const ERROR_AN_HYPHEN			= "alphaNumHyphenError";
	const ERROR_AN_HYPHEN_SPACE		= "alphaNumHyphenSpaceError";
	const ERROR_AN_DOT_U			= "alphaNumDotUError";
	const ERROR_PHONE				= "phoneError";

	// Errors - Create/Register User
	const ERROR_EMAIL_EXIST			= "emailExistError";
	const ERROR_USERNAME_EXIST 		= "usernameExistError";
	const ERROR_PASSWORD			= "passwordError";
	const ERROR_TERMS				= "termsError";

	// Errors - User Login
	const ERROR_USER_NOT_EXIST		= "userNotExistError";	
	const ERROR_USER_VERIFICATION	= "userVerificationError";
	const ERROR_BLOCKED				= "blockedError";
	const ERROR_LOGIN_FAILED		= "loginFailedError";
	const ERROR_PERMISSION			= "permissionError";

	// Errors - User Account
	const ERROR_PASSWORD_OLD		= "oldPasswordError";
	const ERROR_ACCOUNT_CONFIRM		= "confirmAccountError";
	const ERROR_PASSWORD_RESET		= "resetPasswordError";

	// Generic Messages
	const MESSAGE_REQUEST 			= "requestMessage";

	// Messages - Create/Register User
	const MESSAGE_REGISTER 			= "registerMessage";
	const MESSAGE_ACCOUNT_CONFIRM 	= "acctConfirmMessage";
	const MESSAGE_FORGOT_PASSWORD 	= "forgotPwdMessage";
	const MESSAGE_RESET_PASSWORD 	= "resetPwdMessage";

	// Variables ---------------------------------------------------

	public $errorsDb = [
		// Errors - Generic
		self::ERROR_REQUEST => 'Your request was not processed. Please correct the highlighted errors and submit again.',
		self::ERROR_NOT_FOUND => 'The requested resource does not exist.',
		self::ERROR_NOT_ALLOWED => 'You are not allowed to perform this action.',
		self::ERROR_EXIST => 'An entry with the same name already exist. Please provide a different name.',
		self::ERROR_SELECT => 'Please choose a valid value.',
		self::ERROR_URL_ENTITY => 'An entry with the same url already exist.',
		// Errors - Validators
		self::ERROR_AN_SPACE => 'Please provide a valid value without any special character.',
		self::ERROR_AN_PUN => "Please provide valid value having alphanumeric and (? ! . , \" ').",
		self::ERROR_AN_HYPHEN => 'Please provide valid value having alphanumeric and hyphen.',
		self::ERROR_AN_HYPHEN_SPACE => 'Please provide valid value having alphanumeric, space and hyphen.',
		self::ERROR_AN_DOT_U => 'Please provide valid value having alphanumeric, dot(.) or underscore(_) characters.',
		self::ERROR_PHONE => 'Please provide a valid number having numeric digits or hyphen(-), space or plus(+) at beginning.',
		// Errors - Create/Register User
		self::ERROR_EMAIL_EXIST => 'A user already exist with the same Email.',
		self::ERROR_USERNAME_EXIST => 'A user already exist with the same username.',
		self::ERROR_PASSWORD => 'Please provide a valid password having at least 5 characters.',
		self::ERROR_TERMS => 'Please accept our terms and conditions to continue.',
		self::ERROR_USER_NOT_EXIST => 'A user with the provided email is not registered.',	
		self::ERROR_USER_VERIFICATION	=> 'The account with this email is not confirmed yet. Please follow the mail sent while registration or try to reset password.',
		self::ERROR_BLOCKED => 'The account is blocked by Admin. Please contact Admin to re-activate it.',
		self::ERROR_LOGIN_FAILED => 'The provided username and password does not match.',
		// Errors - User Account
		self::ERROR_ACCOUNT_CONFIRM => 'Either your account does not exist or the confirmation link is not valid. Please try to reset your password.',
		self::ERROR_PASSWORD_RESET => 'Either your account does not exist or the reset link is not valid. Please try to reset your password.',
		// Messages
		self::MESSAGE_REQUEST => 'Your request was processed successfully.',
		// Messages - Create/Register User
		self::MESSAGE_REGISTER => 'Thanks for creating your account. A confirmation email having activation link was sent to the given email address.',
		self::MESSAGE_ACCOUNT_CONFIRM => 'Thanks for confirming your account. Please login to continue with us.',
		self::MESSAGE_FORGOT_PASSWORD => 'A confirmation email having password reset link was sent to the given email address.',
		self::MESSAGE_RESET_PASSWORD => 'Your password reset request was processed successfully. Please login to continue with us.'
	];

	/**
	 * Initialise the Core Message DB Component.
	 */
    public function init() {

        parent::init();
    }

	public function getMessage( $messageKey ) {

		return $this->errorsDb[ $messageKey ];
	}
}

?>