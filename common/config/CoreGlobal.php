<?php
namespace cmsgears\modules\core\common\config;

/**
 * The CoreGlobal class defines the global variables available for core and other modules. It list the views name for the various
 * mails triggered by core. It also define the errors and messages available within core module. These error and message codes are
 * used by MessageUtility class.
 */
class CoreGlobal {

	// Categories available for Core Module
	const CATEGORY_TYPE			= "category type";
	const CATEGORY_ROLE_TYPE	= "role type";
	const CATEGORY_CONFIG_TYPE	= "config type";
	const CATEGORY_GENDER		= "gender";

	// Config Type
	const CONFIG_CORE			= "Core";
	const CONFIG_MAIL			= "Email";

	// Various mail views	
	const MAIL_REG				= "register";
	const MAIL_REG_ADMIN		= "register-admin";	
	const MAIL_REG_CONFIRM		= "account-confirm";
	const MAIL_FORGOT_PASSWORD	= "forgot-password";

	// Errors - Generic
	const ERROR_REQUEST				= "requestError";
	const ERROR_NOT_FOUND			= "notFoundError";
	const ERROR_NOT_ALLOWED			= "notAllowedError";
	const ERROR_EXIST				= "entryExistError";
	const ERROR_AN_SPACE			= "alphaNumSpaceError";
	const ERROR_AN_PUN				= "alphaNumPunError";
	const ERROR_AN_HYPHEN			= "alphaNumHyphenError";
	const ERROR_AN_HYPHEN_SPACE		= "alphaNumHyphenSpaceError";
	const ERROR_AN_DOT_U			= "alphaNumDotUError";
	const ERROR_PHONE				= "phoneError";
	const ERROR_SELECT				= "selectError";
	const ERROR_URL_ENTITY 			= "urlEntityError";

	// Errors - Create/Register User
	const ERROR_USER_EXIST			= "userExistError";
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

	// Messages
	const MESSAGE_REQUEST 			= "requestMessage";
	const MESSAGE_CONTACT 			= "contactMessage";
	const MESSAGE_FEEDBACK 			= "feedbackMessage";
	const MESSAGE_REGISTER 			= "registerMessage";
	const MESSAGE_ACCOUNT_CONFIRM 	= "acctConfirmMessage";
	const MESSAGE_FORGOT_PASSWORD 	= "forgotPwdMessage";
	const MESSAGE_RESET_PASSWORD 	= "resetPwdMessage";
}

?>