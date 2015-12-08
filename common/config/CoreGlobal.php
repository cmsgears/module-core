<?php
namespace cmsgears\core\common\config;

/**
 * The CoreGlobal class defines the global constants and variables available for core and dependent modules.
 */
class CoreGlobal {

	// System Pages ---------------------------------------------------

	const PAGE_ACCOUNT_ACTIVATE	= 'activate-account';
	const PAGE_PASSWORD_FORGOT	= 'forgot-password';
	const PAGE_PASSWORD_RESET	= 'reset-password';
	const PAGE_LOGIN			= 'login';

	const FLASH_GENERIC 		= 'message';
	const MODEL_GENERIC 		= 'model';
	const MODEL_EMAIL	 		= 'email';

	// Model Traits - Metas, Attachments, Addresses --------------------

	const TYPE_SYSTEM			= 'system';
	const TYPE_COMBO			= 'combo';
	const TYPE_SITE				= 'site';
	const TYPE_USER				= 'user';
	const TYPE_GALLERY			= 'gallery';
	const TYPE_ACTIVITY			= 'activity';
	const TYPE_NOTIFICATION		= 'notification';
	const TYPE_REMINDER			= 'reminder';  
	const TYPE_CORE				= 'core';
	const TYPE_FORM				= 'form';

	// Categories ------------------------------------------------------

	const CATEGORY_GENDER		= 'gender';

	// Config ----------------------------------------------------------

	const CONFIG_CORE			= 'core';
	const CONFIG_MAIL			= 'mail';
	const CONFIG_ADMIN			= 'backend';
	const CONFIG_FRONTEND		= 'frontend';

	// Roles -----------------------------------------------------------

	const ROLE_USER					= 'user';

	// Permissions -----------------------------------------------------

	// Site Module
	const PERM_ADMIN				= 'admin'; 	// Allows to view Admin Site Home
	const PERM_USER					= 'user'; 	// Allows to view User Site Home

	// System Forms, Galleries, Settings
	const PERM_CORE					= 'core';

	// User Module
	const PERM_IDENTITY				= 'identity';
	const PERM_RBAC					= 'rbac';

	// TODO: Implement internationalisation for Messages, Errors and Field Labels

	// Messages --------------------------------------------------------

	// Generic Messages
	const MESSAGE_REQUEST 			= 'requestMessage';

	// Messages - Create/Register User
	const MESSAGE_REGISTER 			= 'registerMessage';
	const MESSAGE_ACCOUNT_CONFIRM 	= 'acctConfirmMessage';
	const MESSAGE_FORGOT_PASSWORD 	= 'forgotPwdMessage';
	const MESSAGE_RESET_PASSWORD 	= 'resetPwdMessage';

	const MESSAGE_NEWSLETTER_SIGNUP = 'newsletterlSignupMessage';

	// Errors ----------------------------------------------------------

	// Errors - Generic
	const ERROR_REQUEST				= 'requestError';
	const ERROR_NOT_FOUND			= 'notFoundError';
	const ERROR_NOT_ALLOWED			= 'notAllowedError';
	const ERROR_EXIST				= 'entryExistError';
	const ERROR_SELECT				= 'selectError';
	const ERROR_URL_ENTITY 			= 'urlEntityError';
	const ERROR_DEPENDENCY			= 'dependencyError';
	const ERROR_SESSION_OVER		= 'sessionOverError';

	// Errors - Core Validators
	const ERROR_AN_SPACE			= 'alphaNumSpaceError';
	const ERROR_AN_PUN				= 'alphaNumPunError';
	const ERROR_AN_HYPHEN			= 'alphaNumHyphenError';
	const ERROR_AN_HYPHEN_SPACE		= 'alphaNumHyphenSpaceError';
	const ERROR_AN_DOT_U			= 'alphaNumDotUError';
	const ERROR_AN_U				= 'alphaNumUError';
	const ERROR_PHONE				= 'phoneError';

	// Errors - Create/Register User
	const ERROR_EMAIL_EXIST			= 'emailExistError';
	const ERROR_USERNAME_EXIST 		= 'usernameExistError';
	const ERROR_PASSWORD			= 'passwordError';
	const ERROR_TERMS				= 'termsError';

	// Errors - User Login
	const ERROR_USER_NOT_EXIST		= 'userNotExistError';	
	const ERROR_USER_VERIFICATION	= 'userVerificationError';
	const ERROR_BLOCKED				= 'blockedError';
	const ERROR_LOGIN_FAILED		= 'loginFailedError';
	const ERROR_PERMISSION			= 'permissionError';

	// Errors - User Account
	const ERROR_PASSWORD_OLD		= 'oldPasswordError';
	const ERROR_ACCOUNT_CONFIRM		= 'confirmAccountError';
	const ERROR_PASSWORD_RESET		= 'resetPasswordError';
	const ERROR_CHANGE_EMAIL		= 'changeEmailError';
	const ERROR_CHANGE_USERNAME		= 'changeUsernameError';

	// Errors - Apis
	const ERROR_APIS_DISABLED		= 'apisDisabledError';

	// Model Fields ----------------------------------------------------

	// Generic Fields
	const FIELD_CODE				= 'codeField';
	const FIELD_NAME				= 'nameField';
	const FIELD_LABEL				= 'labelField';
	const FIELD_TITLE				= 'titleField';
	const FIELD_ACTIVE				= 'activeField';
	const FIELD_VALUE				= 'valueField';
	const FIELD_MESSAGE				= 'messageField';
	const FIELD_MESSAGE_SUCCESS		= 'messageSuccessField';
	const FIELD_MESSAGE_FAILURE		= 'messageFailureField';
	const FIELD_DESCRIPTION			= 'descField';
	const FIELD_SESSION				= 'sessionField';
	const FIELD_SLUG				= 'slugField';
	const FIELD_LIMIT				= 'limitField';
	const FIELD_CREATED_AT			= 'createdAtField';
	const FIELD_MODIFIED_AT			= 'modifiedAtField';
	const FIELD_VISIBILITY			= 'visibilityField';
	const FIELD_STATUS				= 'statusField';
	const FIELD_TYPE				= 'typeField';
	const FIELD_VALIDATORS 			= 'validatorsField';
	const FIELD_CONSUMED			= 'consumedField';
	const FIELD_DEFAULT 			= 'Default';
	const FIELD_ORDER				= 'orderField';
	const FIELD_FEATURED			= 'featuredField';
	const FIELD_TIME				= 'timeField';
	const FIELD_ICON				= 'iconField';
	const FIELD_ROLE				= 'roleField';
	const FIELD_PERMISSION			= 'permissionField';
	const FIELD_GENDER				= 'genderField';
	const FIELD_PARENT				= 'parentField';
	const FIELD_PARENT_TYPE			= 'parentTypeField';
	const FIELD_LOCALE				= 'localeField';
	const FIELD_PROVINCE			= 'provinceField';
	const FIELD_COUNTRY				= 'countryField';
	const FIELD_CATEGORY			= 'categoryField';
	const FIELD_FILE				= 'fileField';
	const FIELD_TAG					= 'tagField';
	const FIELD_AVATAR				= 'avatarField';
	const FIELD_BANNER				= 'bannerField';
	const FIELD_VIDEO				= 'videoField';
	const FIELD_USER				= 'userField';
	const FIELD_AUTHOR				= 'authorField';
	const FIELD_MEMBER				= 'memberField';
	const FIELD_OWNER				= 'ownerField';
	const FIELD_TEMPLATE			= 'templateField';
	const FIELD_EVENT				= 'eventField';
	const FIELD_EVENT_LOG			= 'eventLogField';
	const FIELD_START_DATE			= 'startDateField';
	const FIELD_END_DATE			= 'endDateField';
	const FIELD_DAY_WEEK 			= 'Week Day';
	const FIELD_DAY_MONTH			= 'Month Day';
	const FIELD_IP					= 'ipField';
	const FIELD_DATA				= 'dataField';
	const FIELD_OPTIONS				= 'optionsField';
	const FIELD_COMPRESS			= 'compressField';

	// Role Fields
	const FIELD_HOME_URL			= 'homeUrlField';

	// Address Fields
	const FIELD_LINE1				= 'line1Field';
	const FIELD_LINE2				= 'line2Field';
	const FIELD_LINE3				= 'line3Field';
	const FIELD_CITY				= 'cityField';
	const FIELD_ZIP					= 'zipField';
	const FIELD_PHONE				= 'phoneField';
	const FIELD_FAX					= 'faxField';
	const FIELD_LONGITUDE			= 'longitudeField';
	const FIELD_LATITUDE			= 'latitudeField';
	const FIELD_ADDRESS				= 'addressField';
	const FIELD_ADDRESS_TYPE		= 'addressTypeField';

	// User Fields
	const FIELD_EMAIL				= 'emailField';
	const FIELD_USERNAME			= 'usernameField';
	const FIELD_PASSWORD			= 'passwordField';
	const FIELD_PASSWORD_REPEAT		= 'passwordRepeatField';
	const FIELD_FIRSTNAME			= 'firstNameField';
	const FIELD_LASTNAME			= 'lastNameField';
	const FIELD_DOB					= 'dobField';
	const FIELD_TERMS				= 'termsField';
	const FIELD_NEWSLETTER			= 'newsletterField';
	
	// File Fields
	const FIELD_EXTENSION			= 'extensionField';
	const FIELD_DIRECTORY			= 'directoryField';
	const FIELD_URL					= 'urlField';
	const FIELD_LINK				= 'linkField';

	// Notification/Reminder/Message Fields
	const FIELD_NOTIFIER		= 'notifierField';
	const FIELD_SENDER			= 'senderField';
	const FIELD_RECIPIENT		= 'recipientField';

	// Site/Site Member Fields
	const FIELD_SITE			= 'siteField';

	// Content Fields
	const FIELD_SUMMARY			= 'summaryField';
	const FIELD_CONTENT			= 'contentField';

	// Views
	const FIELD_LAYOUT			= 'layoutField';
	const FIELD_VIEW_PATH		= 'viewPathField';
	const FIELD_VIEW_ADMIN		= 'viewAdminField';
	const FIELD_VIEW_FRONTEND	= 'viewFrontendField';
	
	// Forms
	const FIELD_FORM			= 'formField';
	const FIELD_CAPTCHA			= 'captchaField';
	const FIELD_MAIL_USER		= 'userMailField';
	const FIELD_MAIL_ADMIN		= 'adminMailField';
	const FIELD_META			= 'metaField';
	const FIELD_RATING			= 'ratingField';
}

?>