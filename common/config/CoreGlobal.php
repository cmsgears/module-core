<?php
namespace cmsgears\core\common\config;

/**
 * The CoreGlobal class defines the global constants and variables available for core and other modules. 
 * It also define the errors and messages available within core module. These error and message codes are 
 * used by MessageUtility class.
 */
class CoreGlobal {

	// Categories available for Core Module
	const CATEGORY_TYPE			= "category type";
	const CATEGORY_ROLE_TYPE	= "role type";
	const CATEGORY_CONFIG_TYPE	= "config type";
	const CATEGORY_GENDER		= "gender";

	// Type of Config available for core module
	const CONFIG_CORE			= "Core";
	const CONFIG_MAIL			= "Email";

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
}

?>