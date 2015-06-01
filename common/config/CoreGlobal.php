<?php
namespace cmsgears\core\common\config;

/**
 * The CoreGlobal class defines the global constants and variables available for core and dependent modules.
 */
class CoreGlobal {

	// Category Type ---------------------------------------------------

	const CATEGORY_TYPE_COMBO	= "combo";

	// Categories ------------------------------------------------------

	const CATEGORY_ROLE_TYPE	= "role type";
	const CATEGORY_GENDER		= "gender";
	const CATEGORY_NOTIFICATION	= "notification";
	const CATEGORY_REMINDER		= "reminder";

	// Model Traits - Metas, Attachments, Addresses --------------------

	const TYPE_SITE				= "site";
	const TYPE_USER				= "user";
	const TYPE_GALLERY			= "gallery";

	// Config ----------------------------------------------------------

	const CONFIG_CORE			= "core";
	const CONFIG_MAIL			= "email";
	const CONFIG_ADMIN			= "admin";
	const CONFIG_FRONTEND		= "frontend";

	// Permissions -----------------------------------------------------

	// Site Module
	const PERM_ADMIN				= "admin"; 	// Allows to view Admin Site Home
	const PERM_USER					= "user"; 	// Allows to view User Site Home

	// Settings
	const PERM_CORE					= "core";

	// User Module
	const PERM_IDENTITY				= "identity";
	const PERM_IDENTITY_RBAC		= "identity-rbac";

	// Messages --------------------------------------------------------

	// Generic Messages
	const MESSAGE_REQUEST 			= "requestMessage";

	// Messages - Create/Register User
	const MESSAGE_REGISTER 			= "registerMessage";
	const MESSAGE_ACCOUNT_CONFIRM 	= "acctConfirmMessage";
	const MESSAGE_FORGOT_PASSWORD 	= "forgotPwdMessage";
	const MESSAGE_RESET_PASSWORD 	= "resetPwdMessage";

	// Errors ----------------------------------------------------------

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
	
	// Errors - Apis
	const ERROR_APIS_DISABLED		= "apisDisabledError";

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_CODE				= "codeField";
	const FIELD_NAME				= "nameField";
	const FIELD_TITLE				= "titleField";
	const FIELD_VALUE				= "valueField";
	const FIELD_MESSAGE				= "messageField";
	const FIELD_DESCRIPTION			= "descField";
	const FIELD_SLUG				= "slugField";
	const FIELD_CREATED_AT			= "createdAtField";
	const FIELD_MODIFIED_AT			= "modifiedAtField";
	const FIELD_VISIBILITY			= "visibilityField";
	const FIELD_STATUS				= "statusField";
	const FIELD_TYPE				= "typeField";
	const FIELD_MARK				= "markField";
	const FIELD_ORDER				= "orderField";
	const FIELD_TIME				= "timeField";
	const FIELD_ICON				= "iconField";
	const FIELD_ROLE				= "roleField";
	const FIELD_PERMISSION			= "permissionField";
	const FIELD_GENDER				= "genderField";
	const FIELD_PARENT				= "parentField";
	const FIELD_PARENT_TYPE			= "parentTypeField";
	const FIELD_LOCALE				= "localeField";
	const FIELD_PROVINCE			= "provinceField";
	const FIELD_COUNTRY				= "countryField";
	const FIELD_CATEGORY			= "categoryField";
	const FIELD_FILE				= "fileField";
	const FIELD_TAG					= "tagField";
	const FIELD_AVATAR				= "avatarField";
	const FIELD_BANNER				= "bannerField";
	const FIELD_USER				= "userField";
	const FIELD_AUTHOR				= "authorField";
	const FIELD_MEMBER				= "memberField";
	const FIELD_OWNER				= "ownerField";

	// Role Fields
	const FIELD_HOME_URL			= "homeUrlField";

	// Address Fields
	const FIELD_LINE1				= "line1Field";
	const FIELD_LINE2				= "line2Field";
	const FIELD_LINE3				= "line3Field";
	const FIELD_CITY				= "cityField";
	const FIELD_ZIP					= "zipField";
	const FIELD_PHONE				= "phoneField";
	const FIELD_FAX					= "faxField";
	const FIELD_ADDRESS				= "addressField";
	const FIELD_ADDRESS_TYPE		= "addressTypeField";

	// User Fields
	const FIELD_EMAIL				= "emailField";
	const FIELD_USERNAME			= "usernameField";
	const FIELD_FIRSTNAME			= "firstNameField";
	const FIELD_LASTNAME			= "lastNameField";
	const FIELD_DOB					= "dobField";
	const FIELD_NEWSLETTER			= "newsletterField";
	
	// File Fields
	const FIELD_EXTENSION			= "extensionField";
	const FIELD_DIRECTORY			= "directoryField";
	const FIELD_URL					= "urlField";
	const FIELD_LINK				= "linkField";

	// Notification/Reminder Fields
	const FIELD_NOTIFIER			= "notifierField";
	
	// Site/Site Member Fields
	const FIELD_SITE				= "siteField";

	// Meta Fields
	const FIELD_META				= "metaField";
	const FIELD_FFIELD_TYPE			= "ffieldTypeField";
	const FIELD_FFIELD_META			= "ffieldMetaField";

	// Content Fields
	const FIELD_SUMMARY			= "summaryField";
	const FIELD_CONTENT			= "contentField";
	
	// Message
	const FIELD_SENDER			= "senderField";
	const FIELD_RECIPIENT		= "recipientField";
}

?>