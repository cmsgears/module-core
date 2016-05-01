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

	// Standard Redirects ----------------------------------------------

	const REDIRECT_LOGIN		= 'loginRedirectLink';

	// Grouping by type ------------------------------------------------

	const TYPE_DEFAULT			= 'default';
	const TYPE_CORE				= 'core';
	const TYPE_SYSTEM			= 'system';

	const TYPE_LOCALE			= 'locale';
	const TYPE_THEME			= 'theme';
	const TYPE_TEMPLATE			= 'template';
	const TYPE_OBJECT			= 'object';
	const TYPE_COUNTRY			= 'country';
	const TYPE_PROVINCE			= 'province';
	const TYPE_CITY				= 'city';
	const TYPE_ROLE				= 'role';
	const TYPE_PERMISSION		= 'permission';
	const TYPE_USER				= 'user';
	const TYPE_SITE				= 'site';

	const TYPE_ACTIVITY			= 'activity';
	const TYPE_CATEGORY			= 'category';
	const TYPE_FORM				= 'form';
	const TYPE_GALLERY			= 'gallery';

	// Templates -------------------------------------------------------

	const TEMPLATE_DEFAULT			= 'default';

	// Template Views --------------------------------------------------

	const TEMPLATE_VIEW_ADMIN		= 'admin';
	const TEMPLATE_VIEW_PRIVATE		= 'private';
	const TEMPLATE_VIEW_PUBLIC		= 'public';
	const TEMPLATE_VIEW_SEARCH		= 'search';
	const TEMPLATE_VIEW_CATEGRY		= 'category';
	const TEMPLATE_VIEW_TAG			= 'tag';
	const TEMPLATE_VIEW_AUTHOR		= 'author';

	// Categories Slug -------------------------------------------------

	const CATEGORY_GENDER		= 'gender';

	const TYPE_COMBO			= 'combo';
	const TYPE_CHECKBOX_GROUP	= 'checkbox-group';

	// Text Limits - Ideal for string validators -----------------------

	const TEXT_SMALL			=  50;
	const TEXT_MEDIUM			= 100;
	const TEXT_LARGE			= 150;
	const TEXT_XLARGE			= 255;

	// Text limit for display ------------------------------------------

	const DISPLAY_TEXT_SMALL	= 160;
	const DISPLAY_TEXT_MEDIUM	= 255;

	// Hierarchy -- Nested Set -----------------------------------------

	const HIERARCHY_VALUE_L		= 1;
	const HIERARCHY_VALUE_R		= 2;

	// Config ----------------------------------------------------------

	const CONFIG_CORE			= 'core';		// Core Config defining site configuration
	const CONFIG_MAIL			= 'mail';		// Mail Config defining mail configurations and useful in case SMTP is required
	const CONFIG_ADMIN			= 'backend';
	const CONFIG_FRONTEND		= 'frontend';

	// Roles -----------------------------------------------------------

	const ROLE_USER					= 'user';

	// Permissions -----------------------------------------------------

	// Site
	const PERM_ADMIN				= 'admin'; 	// Allows to view Admin Site Home
	const PERM_USER					= 'user'; 	// Allows to view User Site Home

	// Site specific - Forms, Galleries, Settings, Testimonials, Categories, Geo DB
	const PERM_CORE					= 'core';

	// User
	const PERM_IDENTITY				= 'identity';	// Allows admin to manage site users
	const PERM_RBAC					= 'rbac';		// Allows admin to manage roles and permissions

	// TODO: Implement internationalisation for Messages, Errors and Field Labels

	// Model Attributes ------------------------------------------------

	// data attributes
	const DATA_REJECT_REASON		= 'rejectReason';

	// model attributes
	const ATTRIBUTE_TYPE_USER		= 'user';
	const ATTRIBUTE_TYPE_SETTING	= 'setting';

	const ATTRIBUTE_COMMENT_SPAM_REQUEST    = 'spam-request';
	const ATTRIBUTE_COMMENT_DELETE_REQUEST  = 'delete-request';

	// Common Status ---------------------------------------------------

	// Active/Inactive
	const STATUS_INACTIVE	= 0;
	const STATUS_ACTIVE		= 1;

	// Messages --------------------------------------------------------

	// Generic Messages
	const MESSAGE_REQUEST 			= 'requestMessage';

	// Messages - Create/Register User
	const MESSAGE_REGISTER 			= 'registerMessage';
	const MESSAGE_ACCOUNT_CONFIRM 	= 'acctConfirmMessage';
	const MESSAGE_FORGOT_PASSWORD 	= 'forgotPwdMessage';
	const MESSAGE_RESET_PASSWORD 	= 'resetPwdMessage';

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
	const ERROR_AN_SPACE_U			= 'alphaNumSpaceUError';
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

	// Errors - View
	const ERROR_NO_TEMPLATE		= 'noTemplateError';
	const ERROR_NO_VIEW			= 'noViewError';

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
	const FIELD_VALUE_TYPE			= 'valueTypeField';
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
	const FIELD_AVATAR_URL			= 'avatarUrlField';
	const FIELD_WEBSITE				= 'websiteField';
	const FIELD_BANNER				= 'bannerField';
	const FIELD_VIDEO				= 'videoField';
	const FIELD_GALLERY				= 'galleryField';
	const FIELD_USER				= 'userField';
	const FIELD_AUTHOR				= 'authorField';
	const FIELD_MEMBER				= 'memberField';
	const FIELD_OWNER				= 'ownerField';
	const FIELD_TEMPLATE			= 'templateField';
	const FIELD_RENDERER			= 'rendererField';
	const FIELD_EVENT				= 'eventField';
	const FIELD_EVENT_LOG			= 'eventLogField';
	const FIELD_DATE_START			= 'startDateField';
	const FIELD_DATE_END			= 'endDateField';
	const FIELD_TIME_START			= 'startTimeField';
	const FIELD_TIME_END			= 'endTimeField';
	const FIELD_DAY_WEEK 			= 'Week Day';
	const FIELD_DAY_MONTH			= 'Month Day';
	const FIELD_IP					= 'ipField';
	const FIELD_AGENT_BROWSER		= 'browserAgentField';
	const FIELD_DATA				= 'dataField';
	const FIELD_HTML_OPTIONS		= 'htmlOptionsField';
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
	const FIELD_ZOOM				= 'zoomField';
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

	// File Fields
	const FIELD_EXTENSION			= 'extensionField';
	const FIELD_DIRECTORY			= 'directoryField';
	const FIELD_SIZE				= 'sizeField';
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
	const FIELD_STYLE			= 'styleField';
	const FIELD_BASE_PATH		= 'basePathField';
	const FIELD_VIEW_PATH		= 'viewPathField';
	const FIELD_VIEW_COUNT		= 'viewCountField';

	// Forms
	const FIELD_FORM			= 'formField';
	const FIELD_CAPTCHA			= 'captchaField';
	const FIELD_MAIL_USER		= 'userMailField';
	const FIELD_MAIL_ADMIN		= 'adminMailField';
	const FIELD_META			= 'metaField';
	const FIELD_RATING			= 'ratingField';

	// Visibility
	const FIELD_PRIVATE			= 'privateField';
	const FIELD_PUBLIC			= 'publicField';
}

?>